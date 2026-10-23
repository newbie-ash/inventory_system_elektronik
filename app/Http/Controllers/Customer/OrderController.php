<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected MidtransService $midtransService) {}

    /**
     * Display listing of customer's own purchase history.
     */
    public function index(): View
    {
        $orders = Sale::with(['items.product'])
            ->where(function ($q) {
                $q->where('buyer_id', Auth::id())
                    ->orWhere('user_id', Auth::id());
            })
            ->latest('sold_at')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Store new customer order with multi-item and Midtrans Snap generation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Keranjang belanja Anda masih kosong.',
            'items.*.quantity.min' => 'Kuantitas pesanan minimal 1 unit.',
        ]);

        $sale = DB::transaction(function () use ($validated) {
            $prefix = 'SL-'.date('Ymd').'-';
            $countToday = Sale::whereDate('created_at', today())->count() + 1;
            $saleNumber = $prefix.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'buyer_id' => Auth::id(),
                'sale_number' => $saleNumber,
                'total' => 0,
                'status' => 'completed',
                'payment_status' => 'pending',
                'payment_method' => 'midtrans',
                'notes' => $validated['notes'] ?? 'Pesanan Pembeli Online',
                'sold_at' => now(),
            ]);

            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk {$product->name} tidak mencukupi (Tersisa: {$product->stock}, Dipesan: {$qty}).",
                    ]);
                }

                $unitPrice = (float) ($product->selling_price > 0 ? $product->selling_price : $product->price);
                $subtotal = round($unitPrice * $qty, 2);

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Atomically decrement stock
                $product->decrement('stock', $qty);

                // Record audit trail
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $qty,
                    'notes' => "Pesanan Online Pembeli #{$sale->sale_number}",
                ]);

                $grandTotal += $subtotal;
            }

            $sale->update(['total' => $grandTotal]);

            return $sale;
        });

        // Generate Midtrans Snap token
        $this->midtransService->createSnapToken($sale);

        return redirect()->route('customer.orders.show', $sale)
            ->with('success', "Pesanan #{$sale->sale_number} berhasil dibuat. Silakan selesaikan pembayaran via Midtrans Snap.");
    }

    /**
     * Display details of a specific customer order.
     */
    public function show(Sale $sale): View
    {
        // Enforce server-side authorization: buyer can only view their own order
        if ($sale->buyer_id !== Auth::id() && $sale->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki akses ke pesanan ini.');
        }

        $sale->load(['items.product', 'buyer', 'user']);

        if (empty($sale->snap_token) && $sale->payment_status !== 'paid') {
            $this->midtransService->createSnapToken($sale);
            $sale->refresh();
        }

        $clientKey = $this->midtransService->getClientKey();
        $snapUrl = $this->midtransService->getSnapUrl();

        return view('customer.orders.show', compact('sale', 'clientKey', 'snapUrl'));
    }

    /**
     * Sandbox demo payment simulation helper.
     */
    public function simulatePayment(Sale $sale): RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $sale->update([
            'payment_status' => 'paid',
        ]);

        return redirect()->route('customer.orders.show', $sale)
            ->with('success', 'Pembayaran Midtrans Sandbox Berhasil Dikonfirmasi (Lunas).');
    }
}
