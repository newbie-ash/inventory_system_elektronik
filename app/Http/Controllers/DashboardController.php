<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the visual dashboard tailored for the authenticated user's role.
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user && $user->isPembeli()) {
            $myOrdersCount = Sale::where('buyer_id', $user->id)->count();
            $myOrdersPending = Sale::where('buyer_id', $user->id)
                ->where('payment_status', 'pending')
                ->where('status', 'completed')
                ->latest('sold_at')
                ->first();
            $recentOrders = Sale::with('items.product')
                ->where('buyer_id', $user->id)
                ->latest('sold_at')
                ->take(5)
                ->get();
            $latestProducts = Product::availableForBuyer()
                ->latest()
                ->take(8)
                ->get();

            return view('dashboard.customer', compact(
                'myOrdersCount',
                'myOrdersPending',
                'recentOrders',
                'latestProducts'
            ));
        }

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
                'total_inventory_value' => 'Rp '.number_format($data['totalInventoryValue'], 0, ',', '.'),
                'sales_today' => 'Rp '.number_format($data['salesToday'], 0, ',', '.'),
                'sales_this_month' => 'Rp '.number_format($data['salesThisMonth'], 0, ',', '.'),
                'total_transactions' => number_format($data['totalTransactions']),
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

        // 2. Total Penjualan Hari Ini & Bulan Ini (Aktual database)
        $salesToday = (float) Sale::where('status', 'completed')
            ->whereDate('sold_at', today())
            ->sum('total');

        $salesThisMonth = (float) Sale::where('status', 'completed')
            ->whereMonth('sold_at', now()->month)
            ->whereYear('sold_at', now()->year)
            ->sum('total');

        // 3. Total Jumlah Transaksi Penjualan
        $totalTransactions = Sale::where('status', 'completed')->count();

        // 4. Total Nilai Inventory (Aggregate SUM(stock * purchase_price/price))
        $totalInventoryValue = (float) (Product::selectRaw('SUM(stock * CASE WHEN purchase_price > 0 THEN purchase_price ELSE price END) as total_val')->value('total_val') ?? 0);

        // 5. Total Mutasi Barang Masuk & Keluar
        $totalStockIn = (int) StockMovement::where('type', 'in')->sum('quantity');
        $totalStockOut = (int) StockMovement::where('type', 'out')->sum('quantity');

        // 6. Produk dengan Stok Rendah
        $lowStockProducts = Product::lowStock()->take(5)->get();
        $lowStockCount = Product::lowStock()->count();

        // Master counts
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // Transaksi Terkini
        $recentSales = Sale::with(['user', 'buyer'])->latest('sold_at')->take(5)->get();
        $recentPurchases = Purchase::with(['supplier', 'user'])->latest('purchased_at')->take(5)->get();

        // Tren Mutasi Stok 7 Hari Terakhir (Chart)
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

        // Distribusi Stok Berdasarkan Kategori
        $categoryDistribution = Category::withSum('products as total_stock', 'stock')
            ->orderByDesc('total_stock')
            ->get();

        $catLabels = $categoryDistribution->pluck('name')->toArray();
        $catStocks = $categoryDistribution->map(fn ($cat) => (int) ($cat->total_stock ?? 0))->toArray();

        // Top 5 Produk Berdasarkan Nilai Inventaris Tertinggi
        $topProducts = Product::select('id', 'name', 'stock', 'price', 'purchase_price', DB::raw('(stock * CASE WHEN purchase_price > 0 THEN purchase_price ELSE price END) as val'))
            ->orderByDesc('val')
            ->take(5)
            ->get();

        $topProductLabels = $topProducts->pluck('name')->toArray();
        $topProductValues = $topProducts->map(fn ($p) => (float) $p->val)->toArray();

        return compact(
            'totalProducts',
            'salesToday',
            'salesThisMonth',
            'totalTransactions',
            'totalInventoryValue',
            'totalStockIn',
            'totalStockOut',
            'lowStockCount',
            'lowStockProducts',
            'recentSales',
            'recentPurchases',
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
