<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Services\PromoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display customer shopping cart items.
     */
    public function index(): View
    {
        $rawCart = session()->get('cart', []);
        $cartItems = [];
        $grandTotal = 0;

        if (! empty($rawCart)) {
            $productIds = array_keys($rawCart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($rawCart as $productId => $quantity) {
                if (isset($products[$productId])) {
                    $product = $products[$productId];
                    $qty = min((int) $quantity, max(1, (int) $product->stock));
                    $unitPrice = (float) ($product->selling_price > 0 ? $product->selling_price : $product->price);
                    $subtotal = round($unitPrice * $qty, 2);

                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];

                    $grandTotal += $subtotal;
                }
            }
        }

        $appliedPromo = session()->get('applied_promo');
        $discountAmount = 0.0;
        $promoCode = null;

        if ($appliedPromo && ! empty($appliedPromo['code'])) {
            $validation = PromoService::validate($appliedPromo['code'], $grandTotal);
            if ($validation['valid']) {
                $discountAmount = (float) $validation['discount'];
                $promoCode = $validation['code'];
            } else {
                session()->forget('applied_promo');
            }
        }

        $finalTotal = max(0.0, $grandTotal - $discountAmount);

        return view('customer.cart', compact('cartItems', 'grandTotal', 'discountAmount', 'promoCode', 'finalTotal'));
    }

    /**
     * Add a product to customer cart.
     */
    public function add(Request $request): RedirectResponse
    {
        if ($request->has('items.0.product_id')) {
            $request->merge([
                'product_id' => $request->input('items.0.product_id'),
                'quantity' => $request->input('items.0.quantity', 1),
            ]);
        }

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantityToAdd = (int) ($validated['quantity'] ?? 1);

        if ($product->stock < 1) {
            return back()->with('error', "Stok produk {$product->name} saat ini sedang habis.");
        }

        $cart = session()->get('cart', []);
        $currentQty = (int) ($cart[$product->id] ?? 0);
        $newQty = $currentQty + $quantityToAdd;

        if ($newQty > $product->stock) {
            $newQty = $product->stock;
            $cart[$product->id] = $newQty;
            session()->put('cart', $cart);

            return back()->with('warning', "Jumlah di keranjang disesuaikan ke stok maksimal ({$product->stock} unit) untuk {$product->name}.");
        }

        $cart[$product->id] = $newQty;
        session()->put('cart', $cart);

        return back()->with('success', "{$product->name} berhasil ditambahkan ke keranjang belanja.");
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $requestedQty = (int) $validated['quantity'];

        if ($requestedQty > $product->stock) {
            return back()->with('error', "Stok tersedia hanya {$product->stock} unit untuk {$product->name}.");
        }

        $cart = session()->get('cart', []);
        $cart[$product->id] = $requestedQty;
        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah produk di keranjang berhasil diperbarui.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', "{$product->name} dihapus dari keranjang belanja.");
    }

    /**
     * Clear all items in the cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget(['cart', 'applied_promo']);

        return back()->with('success', 'Seluruh barang di keranjang belanja berhasil dikosongkan.');
    }

    /**
     * Apply promo code to cart.
     */
    public function applyPromo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'promo_code' => ['required', 'string', 'max:50'],
        ]);

        $rawCart = session()->get('cart', []);
        $grandTotal = 0.0;
        if (! empty($rawCart)) {
            $products = Product::whereIn('id', array_keys($rawCart))->get()->keyBy('id');
            foreach ($rawCart as $pId => $qty) {
                if (isset($products[$pId])) {
                    $p = $products[$pId];
                    $price = (float) ($p->selling_price > 0 ? $p->selling_price : $p->price);
                    $grandTotal += $price * (int) $qty;
                }
            }
        }

        if ($grandTotal <= 0) {
            return back()->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $result = PromoService::validate($validated['promo_code'], $grandTotal);

        if (! $result['valid']) {
            return back()->with('error', $result['message']);
        }

        session()->put('applied_promo', [
            'code' => $result['code'],
            'discount' => $result['discount'],
        ]);

        return back()->with('success', $result['message']);
    }

    /**
     * Remove applied promo code from cart.
     */
    public function removePromo(): RedirectResponse
    {
        session()->forget('applied_promo');

        return back()->with('info', 'Kode promo telah dihapus dari keranjang.');
    }

    /**
     * Re-order items from an existing order into cart (Shopee "Beli Lagi").
     */
    public function reorder(Sale $sale): RedirectResponse
    {
        $cart = session()->get('cart', []);
        $added = 0;

        foreach ($sale->items as $item) {
            $product = $item->product;
            if ($product && $product->stock > 0) {
                $currentQty = $cart[$product->id] ?? 0;
                $newQty = min($currentQty + $item->quantity, (int) $product->stock);
                $cart[$product->id] = $newQty;
                $added++;
            }
        }

        session()->put('cart', $cart);

        if ($added === 0) {
            return redirect()->route('customer.cart')
                ->with('error', 'Produk pada pesanan ini sudah tidak tersedia atau stok habis.');
        }

        return redirect()->route('customer.cart')
            ->with('success', "Barang dari pesanan #{$sale->sale_number} berhasil dimasukkan ke keranjang belanja.");
    }
}
