<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Display a listing of stock movements (audit trail).
     */
    public function index(Request $request): View
    {
        $type = $request->input('type');
        $productId = $request->input('product_id');
        $search = $request->input('search');

        $movements = StockMovement::with(['product.category', 'user'])
            ->when($type && in_array($type, ['in', 'out']), function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->when($productId, function ($query, $productId) {
                return $query->where('product_id', $productId);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('product', function ($pQuery) use ($search) {
                        $pQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    })->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('stock_movements.index', compact('movements', 'products', 'type', 'productId', 'search'));
    }

    /**
     * Show the form for creating a new stock movement transaction.
     */
    public function create(Request $request): View
    {
        $products = Product::with('category')->orderBy('name')->get();
        $selectedProductId = $request->input('product_id');
        $preselectedType = $request->input('type', 'in');

        return view('stock_movements.create', compact('products', 'selectedProductId', 'preselectedType'));
    }

    /**
     * Store a newly created stock movement with strict DB Transaction & Stock validation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'product_id.required' => 'Pilih produk yang akan ditransaksikan.',
            'product_id.exists' => 'Produk yang dipilih tidak ditemukan.',
            'type.required' => 'Jenis transaksi (Masuk/Keluar) wajib ditentukan.',
            'type.in' => 'Jenis transaksi harus berupa "in" atau "out".',
            'quantity.required' => 'Jumlah kuantitas wajib diisi.',
            'quantity.integer' => 'Kuantitas harus berupa angka bulat.',
            'quantity.min' => 'Jumlah kuantitas minimal 1 dan tidak boleh negatif.',
        ]);

        $quantity = (int) $validated['quantity'];
        $type = $validated['type'];

        DB::beginTransaction();

        try {
            // Lock row for concurrent safety
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

            // Business Logic Check: Stok Tidak Boleh Negatif
            if ($type === 'out' && $product->stock < $quantity) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'quantity' => "Jumlah barang keluar ({$quantity}) melebihi stok yang tersedia ({$product->stock}). Stok tidak boleh negatif.",
                    ])
                    ->with('error', "Stok tidak mencukupi! Sisa stok untuk '{$product->name}' hanya {$product->stock} unit.");
            }

            // Update product stock balance
            if ($type === 'in') {
                $product->stock += $quantity;
            } else {
                $product->stock -= $quantity;
            }
            $product->save();
            if ($type === 'out') {
                $product->notifyIfLowStock();
            }

            // Record transaction history
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'quantity' => $quantity,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            $statusText = $type === 'in' ? 'Barang masuk' : 'Barang keluar';

            return redirect()->route('stock-movements.index')
                ->with('success', "Transaksi {$statusText} berhasil dicatat! Sisa stok {$product->name}: {$product->stock} unit.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memproses transaksi stok: '.$e->getMessage());
        }
    }

    /**
     * Display the specified stock movement.
     */
    public function show(StockMovement $stockMovement): View
    {
        $stockMovement->load(['product.category', 'product.suppliers', 'user']);

        return view('stock_movements.show', compact('stockMovement'));
    }
}
