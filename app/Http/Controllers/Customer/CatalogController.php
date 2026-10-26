<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\CustomerActivity;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Display public/buyer catalog (unified with Beranda).
     */
    public function index(Request $request): View
    {
        return app(DashboardController::class)->index();
    }

    /**
     * Display detailed product page for buyer.
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'reviews.user.profile']);

        // Track viewing activity in customer audit trail
        if (Auth::check() && Auth::user()->isPembeli()) {
            $activity = CustomerActivity::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $activity->view_count = ($activity->view_count ?? 0) + 1;
            $activity->last_viewed_at = now();
            $activity->save();
        }

        $userCanReview = false;
        $userDeliveredSale = null;
        $existingReview = null;

        if (Auth::check() && Auth::user()->isPembeli()) {
            $userDeliveredSale = Sale::where(function ($q) {
                $q->where('buyer_id', Auth::id())->orWhere('user_id', Auth::id());
            })->where(function ($q) {
                $q->where('shipping_status', 'delivered')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'completed')->where('payment_status', 'paid');
                    });
            })->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })->latest('sold_at')->first();

            if ($userDeliveredSale) {
                $userCanReview = true;
                $existingReview = $product->reviews->firstWhere('user_id', Auth::id());
            }
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('customer.product_detail', compact(
            'product',
            'relatedProducts',
            'userCanReview',
            'existingReview',
            'userDeliveredSale'
        ));
    }
}
