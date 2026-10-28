<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display comprehensive inventory reports as taught in P4 Slide 14 & Slide 28.
     */
    public function index(Request $request): View
    {
        $data = $this->getReportData($request, true);

        return view('reports.index', $data);
    }

    /**
     * Export official PDF inventory & stock mutation report with Kop Surat.
     */
    public function exportPdf(Request $request): Response
    {
        $data = $this->getReportData($request, false);

        $logoPath = public_path('images/logo.png');
        $data['logoBase64'] = file_exists($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('pdf.inventory_report', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-inventaris-'.date('YmdHis').'.pdf');
    }

    /**
     * Export Excel spreadsheet of inventory & stock movements.
     */
    public function exportExcel(Request $request): Response
    {
        $data = $this->getReportData($request, false);
        $content = view('exports.inventory_excel', $data)->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="rekap-inventaris-'.date('YmdHis').'.xls"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Export Excel spreadsheet of sales transactions & gross profit analysis.
     */
    public function exportSalesExcel(Request $request): Response
    {
        $sales = Sale::with(['user', 'buyer', 'items.product'])->latest('sold_at')->get();

        $totalRevenue = 0;
        $totalCost = 0;
        foreach ($sales as $sale) {
            $totalRevenue += (float) $sale->total;
            foreach ($sale->items as $item) {
                $cost = $item->product ? ($item->product->purchase_price > 0 ? (float) $item->product->purchase_price : 0) : 0;
                $totalCost += ($cost * $item->quantity);
            }
        }
        $totalGrossProfit = max(0, $totalRevenue - $totalCost);

        $content = view('exports.sales_excel', compact(
            'sales',
            'totalRevenue',
            'totalCost',
            'totalGrossProfit'
        ))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="rekap-penjualan-laba-rugi-'.date('YmdHis').'.xls"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Query and prepare shared report dataset.
     */
    protected function getReportData(Request $request, bool $paginate = true): array
    {
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

        $categoryId = $request->input('category_id');
        $search = $request->input('search');

        $query = Product::with('category')
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
            ->when($categoryId, function ($q, $catId) {
                return $q->where('products.category_id', $catId);
            })
            ->when($search, function ($q, $s) {
                return $q->where(function ($sub) use ($s) {
                    $sub->where('products.name', 'like', "%{$s}%")
                        ->orWhere('products.sku', 'like', "%{$s}%");
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
            ->orderBy('products.name');

        $productReports = $paginate ? $query->paginate(15)->withQueryString() : $query->get();

        $totalProducts = Product::count();
        $totalInventoryValue = (float) (Product::selectRaw('SUM(stock * price) as val')->value('val') ?? 0);
        $grandTotalIn = (int) StockMovement::where('type', 'in')->sum('quantity');
        $grandTotalOut = (int) StockMovement::where('type', 'out')->sum('quantity');
        $categories = Category::orderBy('name')->get();
        $selectedCategoryName = $categoryId ? Category::find($categoryId)?->name : 'Semua Kategori';

        return compact(
            'categoryReports',
            'productReports',
            'totalProducts',
            'totalInventoryValue',
            'grandTotalIn',
            'grandTotalOut',
            'categories',
            'categoryId',
            'selectedCategoryName',
            'search'
        );
    }
}
