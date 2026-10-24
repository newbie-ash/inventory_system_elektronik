<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Product;
use Illuminate\Http\Request;
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
        $product->load('category');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('customer.product_detail', compact('product', 'relatedProducts'));
    }
}
