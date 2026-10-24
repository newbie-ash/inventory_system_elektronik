<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Display the REDMAGIC-style public gaming hardware landing page.
     */
    public function index(Request $request)
    {
        $query = Product::availableForBuyer()->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('stock', '>', 0);
        }])->orderByDesc('products_count')->get();

        $featuredProducts = Product::availableForBuyer()->where('stock', '>', 3)->latest()->take(3)->get();
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::availableForBuyer()->latest()->take(3)->get();
        }

        $totalAvailableProducts = Product::availableForBuyer()->count();

        return view('landing', compact('products', 'categories', 'featuredProducts', 'totalAvailableProducts'));
    }

    /**
     * Handle product card click: redirect guest to login or authenticated user to product detail.
     */
    public function previewProduct(Product $product)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isPembeli()) {
                return redirect()->route('customer.catalog.show', $product);
            }

            return redirect()->route('products.show', $product);
        }

        // Guest clicked product: redirect to login form with notice and intended URL
        return redirect()->route('login', [
            'intended' => route('customer.catalog.show', $product),
            'product' => $product->name,
        ])->with('info', "Silakan masuk ke akun Anda untuk memesan {$product->name}.");
    }
}
