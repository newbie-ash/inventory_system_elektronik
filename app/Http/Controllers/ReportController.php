<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display comprehensive inventory reports as taught in P4 Slide 14 & Slide 28.
     */
    public function index(Request $request): View
    {
        // 1. Laporan Agregat per Kategori (P4 Slide 14)
        // SELECT c.name, COUNT(p.id) AS total_products, SUM(p.stock) AS total_stock, AVG(p.price) AS average_price
        $categoryReports = Category::leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(products.id) as total_products'),
                DB::raw('COALESCE(SUM(products.stock), 0) as total_stock'),
                DB::raw('COALESCE(AVG(products.price), 0) as average_price'),
                DB::raw('COALESCE(SUM(products.stock * products.price), 0) as category_inventory_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();

        // 2. Laporan Pergerakan Stok per Produk (P4 Slide 28)
        // SELECT p.sku, p.name, SUM(CASE WHEN sm.type='in' THEN sm.quantity ELSE 0 END) AS total_in, ...
        $categoryId = $request->input('category_id');
        $search = $request->input('search');

        $productReports = Product::with('category')
            ->leftJoin('stock_movements', 'products.id', '=', 'stock_movements.product_id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                'products.price',
                'products.stock as current_stock',
                'products.min_stock',
                'products.category_id',
                DB::raw("COALESCE(SUM(CASE WHEN stock_movements.type = 'in' THEN stock_movements.quantity ELSE 0 END), 0) as total_in"),
                DB::raw("COALESCE(SUM(CASE WHEN stock_movements.type = 'out' THEN stock_movements.quantity ELSE 0 END), 0) as total_out")
            )
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('products.category_id', $categoryId);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                      ->orWhere('products.sku', 'like', "%{$search}%");
                });
            })
            ->groupBy(
                'products.id',
                'products.sku',
                'products.name',
                'products.price',
                'products.stock',
                'products.min_stock',
                'products.category_id'
            )
            ->orderBy('products.name')
            ->paginate(15)
            ->withQueryString();

        // 3. Ringkasan Keseluruhan (KPI)
        $totalProducts = Product::count();
        $totalInventoryValue = (float) (Product::selectRaw('SUM(stock * price) as val')->value('val') ?? 0);
        $grandTotalIn = (int) StockMovement::where('type', 'in')->sum('quantity');
        $grandTotalOut = (int) StockMovement::where('type', 'out')->sum('quantity');

        $categories = Category::orderBy('name')->get();

        return view('reports.index', compact(
            'categoryReports',
            'productReports',
            'totalProducts',
            'totalInventoryValue',
            'grandTotalIn',
            'grandTotalOut',
            'categories',
            'categoryId',
            'search'
        ));
    }
}
