<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the visual dashboard with charts and real-time statistics.
     */
    public function index(): View
    {
        $data = $this->getDashboardData();

        return view('dashboard.index', $data);
    }

    /**
     * Provide real-time data endpoint for Chart.js AJAX polling.
     */
    public function realtimeData(): JsonResponse
    {
        $data = $this->getDashboardData();

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->translatedFormat('H:i:s'),
            'kpis' => [
                'total_products' => number_format($data['totalProducts']),
                'total_inventory_value' => 'Rp ' . number_format($data['totalInventoryValue'], 0, ',', '.'),
                'total_stock_in' => number_format($data['totalStockIn']),
                'total_stock_out' => number_format($data['totalStockOut']),
                'low_stock_count' => $data['lowStockCount'],
                'total_categories' => $data['totalCategories'],
                'total_suppliers' => $data['totalSuppliers'],
            ],
            'chart_trends' => [
                'labels' => $data['trendLabels'],
                'stock_in' => $data['trendStockIn'],
                'stock_out' => $data['trendStockOut'],
            ],
            'chart_categories' => [
                'labels' => $data['catLabels'],
                'data' => $data['catStocks'],
            ],
            'chart_top_products' => [
                'labels' => $data['topProductLabels'],
                'data' => $data['topProductValues'],
            ],
        ]);
    }

    /**
     * Compute comprehensive dashboard data and chart metrics.
     */
    private function getDashboardData(): array
    {
        // 1. Total Produk
        $totalProducts = Product::count();

        // 2. Total Nilai Inventory (Aggregate SUM(stock * price))
        $totalInventoryValue = (float) (Product::selectRaw('SUM(stock * price) as total_val')->value('total_val') ?? 0);

        // 3. Total Barang Masuk & Keluar (Aggregate SUM(quantity))
        $totalStockIn = (int) StockMovement::where('type', 'in')->sum('quantity');
        $totalStockOut = (int) StockMovement::where('type', 'out')->sum('quantity');

        // 4. Jumlah Produk dengan Stok Rendah (stock <= min_stock)
        $lowStockCount = Product::lowStock()->count();

        // Additional counts
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // 5. Tren Mutasi Stok 7 Hari Terakhir (Line / Area Chart)
        $trendLabels = [];
        $trendStockIn = [];
        $trendStockOut = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendLabels[] = now()->subDays($i)->translatedFormat('d M');

            $trendStockIn[] = (int) StockMovement::where('type', 'in')
                ->whereDate('created_at', $date)
                ->sum('quantity');

            $trendStockOut[] = (int) StockMovement::where('type', 'out')
                ->whereDate('created_at', $date)
                ->sum('quantity');
        }

        // 6. Distribusi Stok Berdasarkan Kategori (Doughnut Chart)
        $categoryDistribution = Category::withSum('products as total_stock', 'stock')
            ->orderByDesc('total_stock')
            ->get();

        $catLabels = $categoryDistribution->pluck('name')->toArray();
        $catStocks = $categoryDistribution->map(fn ($cat) => (int) ($cat->total_stock ?? 0))->toArray();

        // 7. Top 5 Produk Berdasarkan Nilai Inventaris Tertinggi (Bar Chart)
        $topProducts = Product::select('id', 'name', 'stock', 'price', DB::raw('(stock * price) as val'))
            ->orderByDesc('val')
            ->take(5)
            ->get();

        $topProductLabels = $topProducts->pluck('name')->toArray();
        $topProductValues = $topProducts->map(fn ($p) => (float) $p->val)->toArray();

        return compact(
            'totalProducts',
            'totalInventoryValue',
            'totalStockIn',
            'totalStockOut',
            'lowStockCount',
            'totalCategories',
            'totalSuppliers',
            'trendLabels',
            'trendStockIn',
            'trendStockOut',
            'catLabels',
            'catStocks',
            'topProductLabels',
            'topProductValues'
        );
    }
}
