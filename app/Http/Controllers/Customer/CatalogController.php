<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Display public/buyer catalog with available stock (stock > 0).
     */
    public function index(Request $request): View
    {
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('stock', '>', 0);
        }])->orderBy('name')->get();

        $query = Product::with('category')
            ->where('stock', '>', 0)
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();

        return view('customer.catalog', compact('products', 'categories'));
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
