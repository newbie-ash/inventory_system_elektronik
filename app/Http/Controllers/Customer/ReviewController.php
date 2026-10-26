<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store or update a customer review & rating.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'sale_id' => ['nullable', 'exists:sales,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih rating bintang antara 1 sampai 5.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
        ]);

        $userId = Auth::id();
        $productId = (int) $validated['product_id'];
        $saleId = $validated['sale_id'] ?? null;

        if ($saleId) {
            $sale = Sale::findOrFail($saleId);

            if ($sale->buyer_id !== $userId && $sale->user_id !== $userId) {
                abort(403, 'Akses ditolak: Anda bukan pemilik pesanan ini.');
            }

            $hasItem = $sale->items()->where('product_id', $productId)->exists();
            if (! $hasItem) {
                return back()->with('error', 'Produk ini tidak terdapat dalam pesanan yang dipilih.');
            }
        } else {
            // General review: ensure customer has actually purchased this product in a completed/delivered sale
            $purchased = Sale::where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)->orWhere('user_id', $userId);
            })->where(function ($q) {
                $q->where('shipping_status', 'delivered')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'completed')->where('payment_status', 'paid');
                    });
            })->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })->first();

            if (! $purchased) {
                return back()->with('error', 'Ulasan hanya dapat diberikan oleh pembeli yang telah membeli dan menerima produk ini.');
            }

            $saleId = $purchased->id;
        }

        Review::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'sale_id' => $saleId,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return back()->with('success', "Terima kasih! Penilaian bintang {$validated['rating']} dan ulasan Anda berhasil disimpan.");
    }
}
