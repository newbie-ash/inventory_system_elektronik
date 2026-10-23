<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases with search, filter, and pagination.
     */
    public function index(Request $request): View
    {
        $query = Purchase::with(['supplier', 'user', 'items.product'])
            ->latest('purchased_at');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchased_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchased_at', '<=', $request->input('date_to'));
        }

        $purchases = $query->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase (Stock-In).
     */
    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase transaction with atomic stock updates.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ], [
            'supplier_id.required' => 'Pemasok (Supplier) wajib dipilih.',
            'items.required' => 'Minimal harus menambahkan satu produk pada pembelian.',
            'items.*.quantity.min' => 'Kuantitas barang masuk minimal 1.',
            'items.*.unit_cost.min' => 'Harga modal tidak boleh negatif.',
        ]);

        $purchase = DB::transaction(function () use ($validated) {
            $purchaseNumber = $this->generatePurchaseNumber();

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'purchase_number' => $purchaseNumber,
                'total' => 0,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
                'purchased_at' => $validated['purchased_at'] ?? now(),
            ]);

            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                // Pessimistic Locking to avoid race conditions
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];
                $subtotal = round($unitCost * $qty, 2);

                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                // Increment product stock
                $product->increment('stock', $qty);

                // Update purchase_price on product if entered
                if ($unitCost > 0) {
                    $product->update(['purchase_price' => $unitCost]);
                }

                // Audit log in stock_movements
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $qty,
                    'notes' => "Stock-In Pembelian #{$purchase->purchase_number}",
                ]);

                $grandTotal += $subtotal;
            }

            // Strictly server-side calculated grand total
            $purchase->update(['total' => $grandTotal]);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', "Transaksi Pembelian #{$purchase->purchase_number} berhasil dicatat dan stok bertambah.");
    }

    /**
     * Display the specified purchase details.
     */
    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }

    /**
     * Cancel purchase transaction and reverse added stock (Admin only).
     */
    public function cancel(Request $request, Purchase $purchase): RedirectResponse
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Hanya Administrator yang memiliki wewenang membatalkan transaksi.');
        }

        if ($purchase->status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dalam status dibatalkan.');
        }

        DB::transaction(function () use ($purchase) {
            $purchase->load('items.product');

            foreach ($purchase->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cancel' => "Gagal membatalkan: Stok produk {$product->name} tersisa {$product->stock}, tidak cukup untuk dikurangkan kembali sejumlah {$item->quantity}.",
                    ]);
                }

                $product->decrement('stock', $item->quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'notes' => "Reversal Pembatalan Pembelian #{$purchase->purchase_number}",
                ]);
            }

            $purchase->update(['status' => 'cancelled']);
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', "Transaksi Pembelian #{$purchase->purchase_number} berhasil dibatalkan dan stok dikembalikan.");
    }

    /**
     * Generate unique purchase order number.
     */
    protected function generatePurchaseNumber(): string
    {
        $prefix = 'PO-'.date('Ymd').'-';
        $countToday = Purchase::whereDate('created_at', today())->count() + 1;

        return $prefix.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);
    }
}
