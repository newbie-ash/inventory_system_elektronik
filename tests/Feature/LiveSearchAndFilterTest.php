<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveSearchAndFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $buyer;

    private Category $categoryA;

    private Category $categoryB;

    private Product $product1;

    private Product $product2;

    private Product $product3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->buyer = User::factory()->create(['role' => 'pembeli']);

        $this->categoryA = Category::factory()->create(['name' => 'Laptop Gaming']);
        $this->categoryB = Category::factory()->create(['name' => 'Aksesoris']);

        $this->product1 = Product::factory()->create([
            'category_id' => $this->categoryA->id,
            'name' => 'ASUS ROG Zephyrus G16',
            'sku' => 'LAP-ROG-001',
            'price' => 30000000,
            'selling_price' => 30000000,
            'stock' => 8,
            'min_stock' => 5,
        ]);

        $this->product2 = Product::factory()->create([
            'category_id' => $this->categoryA->id,
            'name' => 'Lenovo Legion 7i',
            'sku' => 'LAP-LEG-002',
            'price' => 25000000,
            'selling_price' => 25000000,
            'stock' => 2,
            'min_stock' => 5,
        ]);

        $this->product3 = Product::factory()->create([
            'category_id' => $this->categoryB->id,
            'name' => 'Logitech G Pro X Superlight',
            'sku' => 'MOU-LOG-001',
            'price' => 1800000,
            'selling_price' => 1800000,
            'stock' => 15,
            'min_stock' => 5,
        ]);
    }

    public function test_admin_ajax_live_search_products_table(): void
    {
        $response = $this->actingAs($this->admin)->get(route('products.index', [
            'ajax' => 1,
            'search' => 'ROG',
        ]), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertOk();
        $response->assertSee('ASUS ROG Zephyrus G16');
        $response->assertDontSee('Lenovo Legion 7i');
        $response->assertDontSee('Logitech G Pro X Superlight');
        $this->assertStringNotContainsString('<!DOCTYPE html>', $response->getContent());
    }

    public function test_admin_ajax_filter_by_stock_status(): void
    {
        // Low stock filter (product2 has stock 2 <= min_stock 5)
        $response = $this->actingAs($this->admin)->get(route('products.index', [
            'ajax' => 1,
            'stock_status' => 'low',
        ]));

        $response->assertOk();
        $response->assertSee('Lenovo Legion 7i');
        $response->assertDontSee('ASUS ROG Zephyrus G16');
    }

    public function test_customer_ajax_live_search_catalog(): void
    {
        $response = $this->actingAs($this->buyer)->get(route('dashboard', [
            'ajax' => 1,
            'search' => 'Superlight',
        ]), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertOk();
        $response->assertSee('Logitech G Pro X Superlight');
        $response->assertDontSee('ASUS ROG Zephyrus G16');
        $this->assertStringNotContainsString('<!DOCTYPE html>', $response->getContent());
    }

    public function test_customer_ajax_filter_by_category(): void
    {
        $response = $this->actingAs($this->buyer)->get(route('dashboard', [
            'ajax' => 1,
            'categories' => [$this->categoryB->id],
        ]));

        $response->assertOk();
        $response->assertSee('Logitech G Pro X Superlight');
        $response->assertDontSee('ASUS ROG Zephyrus G16');
        $response->assertDontSee('Lenovo Legion 7i');
    }

    public function test_customer_ajax_filter_by_price_range(): void
    {
        $response = $this->actingAs($this->buyer)->get(route('dashboard', [
            'ajax' => 1,
            'min_price' => 20000000,
            'max_price' => 28000000,
        ]));

        $response->assertOk();
        $response->assertSee('Lenovo Legion 7i');
        $response->assertDontSee('ASUS ROG Zephyrus G16');
        $response->assertDontSee('Logitech G Pro X Superlight');
    }
}
