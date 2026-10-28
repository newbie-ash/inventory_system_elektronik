<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAndInvoiceExportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $staff;

    private User $buyer;

    private User $otherBuyer;

    private Sale $sale;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
        $this->buyer = User::factory()->create(['role' => 'pembeli']);
        $this->otherBuyer = User::factory()->create(['role' => 'pembeli']);

        $category = Category::factory()->create(['name' => 'Hardware']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'RTX 4080',
            'sku' => 'GPU-4080',
            'stock' => 10,
            'purchase_price' => 15000000,
            'selling_price' => 18000000,
        ]);

        $this->sale = Sale::create([
            'user_id' => $this->buyer->id,
            'buyer_id' => $this->buyer->id,
            'sale_number' => 'SL-20261028-0001',
            'subtotal' => 18000000,
            'total' => 18000000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'packing',
            'sold_at' => now(),
        ]);

        $this->sale->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 18000000,
            'subtotal' => 18000000,
        ]);
    }

    public function test_admin_and_staff_can_download_sales_invoice_pdf(): void
    {
        $responseAdmin = $this->actingAs($this->admin)->get(route('sales.invoice.pdf', $this->sale));
        $responseAdmin->assertOk();
        $this->assertStringContainsString('application/pdf', $responseAdmin->headers->get('Content-Type'));
        $this->assertStringContainsString("Invoice-{$this->sale->sale_number}.pdf", $responseAdmin->headers->get('Content-Disposition'));

        $responseStaff = $this->actingAs($this->staff)->get(route('sales.invoice.pdf', $this->sale));
        $responseStaff->assertOk();
        $this->assertStringContainsString('application/pdf', $responseStaff->headers->get('Content-Type'));
    }

    public function test_customer_can_download_their_own_order_invoice_pdf(): void
    {
        $response = $this->actingAs($this->buyer)->get(route('customer.orders.invoice.pdf', $this->sale));
        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_customer_cannot_download_other_customer_invoice_pdf(): void
    {
        $response = $this->actingAs($this->otherBuyer)->get(route('customer.orders.invoice.pdf', $this->sale));
        $response->assertForbidden();
    }

    public function test_admin_can_export_inventory_report_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.export.pdf'));
        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('laporan-inventaris-', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_export_inventory_mutation_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.export.excel'));
        $response->assertOk();
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rekap-inventaris-', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('CTRL+R HARDWARE', $response->getContent());
    }

    public function test_admin_can_export_sales_profit_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.export.sales-excel'));
        $response->assertOk();
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rekap-penjualan-laba-rugi-', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('SL-20261028-0001', $response->getContent());
    }

    public function test_non_admin_cannot_export_reports(): void
    {
        $response = $this->actingAs($this->buyer)->get(route('reports.export.pdf'));
        $response->assertForbidden();

        $responseExcel = $this->actingAs($this->buyer)->get(route('reports.export.excel'));
        $responseExcel->assertForbidden();
    }
}
