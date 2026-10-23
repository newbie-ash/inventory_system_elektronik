<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(protected MidtransService $midtransService) {}

    /**
     * Display a listing of sales transactions with search, filter, and pagination.
     */
    public function index(Request $request): View
    {
        $query = Sale::with(['user', 'buyer', 'items.product'])
            ->latest('sold_at');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                    ->orWhereHas('buyer', function ($bq) use ($search) {
                        $bq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sold_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sold_at', '<=', $request->input('date_to'));
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale (Stock-Out).
     */
    public function create(): View
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $buyers = User::where('role', 'pembeli')->orderBy('name')->get();

        return view('sales.create', compact('products', 'buyers'));
    }

    /**
     * Store a newly created sale with atomic validation, pessimistic locking, and stock deduction.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'buyer_id' => ['nullable', 'exists:users,id'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Minimal harus menambahkan satu produk pada transaksi penjualan.',
            'items.*.quantity.min' => 'Kuantitas penjualan minimal 1 unit.',
        ]);

        $sale = DB::transaction(function () use ($validated) {
            $saleNumber = $this->generateSaleNumber();

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'buyer_id' => $validated['buyer_id'] ?? null,
                'sale_number' => $saleNumber,
                'total' => 0,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'notes' => $validated['notes'] ?? null,
                'sold_at' => now(),
            ]);

            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                // Pessimistic Lock to guarantee anti-overselling
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                // Strict stock check
                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk {$product->name} tidak mencukupi (Tersisa: {$product->stock}, Diminta: {$qty}).",
                    ]);
                }

                // Never trust browser input: unit price taken straight from DB
                $unitPrice = (float) ($product->selling_price > 0 ? $product->selling_price : $product->price);
                $subtotal = round($unitPrice * $qty, 2);

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Decrement stock atomically
                $product->decrement('stock', $qty);

                // Audit log in stock_movements
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $qty,
                    'notes' => "Stock-Out Penjualan #{$sale->sale_number}",
                ]);

                $grandTotal += $subtotal;
            }

            // Server-side calculated total
            $sale->update(['total' => $grandTotal]);

            return $sale;
        });

        // If Midtrans payment method selected, generate Snap Token
        if ($sale->payment_method === 'midtrans') {
            $this->midtransService->createSnapToken($sale);
        }

        return redirect()->route('sales.show', $sale)
            ->with('success', "Transaksi Penjualan #{$sale->sale_number} berhasil diproses dan stok dikurangi.");
    }

    /**
     * Display the specified sale details.
     */
    public function show(Sale $sale): View
    {
        $sale->load(['user', 'buyer', 'items.product']);

        $snapToken = $sale->snap_token;
        if (empty($snapToken) && $sale->payment_method === 'midtrans' && $sale->payment_status !== 'paid') {
            $snapToken = $this->midtransService->createSnapToken($sale);
        }

        $clientKey = $this->midtransService->getClientKey();
        $snapUrl = $this->midtransService->getSnapUrl();

        return view('sales.show', compact('sale', 'snapToken', 'clientKey', 'snapUrl'));
    }

    /**
     * Cancel sale transaction and restore inventory stock (Admin only).
     */
    public function cancel(Request $request, Sale $sale): RedirectResponse
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Hanya Administrator yang memiliki wewenang membatalkan transaksi.');
        }

        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dalam status dibatalkan.');
        }

        DB::transaction(function () use ($sale) {
            $sale->load('items.product');

            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);

                // Revert stock back to product
                $product->increment('stock', $item->quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'notes' => "Reversal Pembatalan Penjualan #{$sale->sale_number}",
                ]);
            }

            $sale->update([
                'status' => 'cancelled',
                'payment_status' => 'refunded',
            ]);
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', "Transaksi Penjualan #{$sale->sale_number} berhasil dibatalkan dan stok produk telah dipulihkan.");
    }

    /**
     * Generate unique sales invoice number.
     */
    protected function generateSaleNumber(): string
    {
        $prefix = 'SL-'.date('Ymd').'-';
        $countToday = Sale::whereDate('created_at', today())->count() + 1;

        return $prefix.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);
    }
}
