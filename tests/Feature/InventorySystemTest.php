<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->staff = User::factory()->create([
            'role' => 'staff',
        ]);

        $this->category = Category::factory()->create([
            'name' => 'Elektronik',
        ]);
    }

    /**
     * 1. Test Autentikasi: Tamu (Guest) dialihkan ke login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/products');
        $response->assertRedirect('/login');
    }

    /**
     * 2. Test RBAC: Staff hanya bisa melihat produk dan transaksi stok, tapi dilarang mengakses User Admin.
     */
    public function test_staff_cannot_access_user_management_or_admin_routes(): void
    {
        $response = $this->actingAs($this->staff)->get('/users');
        $response->assertStatus(403);

        $response = $this->actingAs($this->staff)->get('/categories/create');
        $response->assertStatus(403);

        $response = $this->actingAs($this->staff)->get('/products/create');
        $response->assertStatus(403);
    }

    /**
     * 3. Test RBAC: Staff diizinkan melihat katalog produk dan transaksi stok.
     */
    public function test_staff_can_view_products_and_record_stock_movements(): void
    {
        $response = $this->actingAs($this->staff)->get('/products');
        $response->assertStatus(200);

        $response = $this->actingAs($this->staff)->get('/stock-movements');
        $response->assertStatus(200);

        $response = $this->actingAs($this->staff)->get('/stock-movements/create');
        $response->assertStatus(200);
    }

    /**
     * 4. Test RBAC: Admin memiliki akses ke seluruh fitur.
     */
    public function test_admin_has_full_access(): void
    {
        $response = $this->actingAs($this->admin)->get('/users');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get('/categories');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get('/suppliers');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get('/products/create');
        $response->assertStatus(200);
    }

    /**
     * 5. Test Relasi Eloquent: Category ke Product dan Many-to-Many Product ke Supplier.
     */
    public function test_eloquent_relationships(): void
    {
        $supplier1 = Supplier::factory()->create();
        $supplier2 = Supplier::factory()->create();

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Mouse Gaming',
            'sku' => 'MOU-GAM-001',
            'price' => 150000,
            'stock' => 20,
            'min_stock' => 5,
        ]);

        $product->suppliers()->attach([$supplier1->id, $supplier2->id]);

        // Verifikasi relasi One-to-Many
        $this->assertEquals($this->category->id, $product->category->id);
        $this->assertTrue($this->category->products->contains($product));

        // Verifikasi relasi Many-to-Many
        $this->assertCount(2, $product->suppliers);
        $this->assertTrue($supplier1->products->contains($product));
    }

    /**
     * 6. Test Listing Produk: Search, Filter, dan Sorting.
     */
    public function test_product_search_filter_and_sorting(): void
    {
        $cat1 = Category::factory()->create(['name' => 'Komputer']);
        $cat2 = Category::factory()->create(['name' => 'Alat Tulis']);

        $prod1 = Product::create([
            'category_id' => $cat1->id,
            'name' => 'Laptop Lenovo',
            'sku' => 'LAP-001',
            'price' => 10000000,
            'stock' => 10,
            'min_stock' => 2,
        ]);

        $prod2 = Product::create([
            'category_id' => $cat2->id,
            'name' => 'Buku Tulis',
            'sku' => 'BKU-001',
            'price' => 5000,
            'stock' => 100,
            'min_stock' => 10,
        ]);

        // Search by name
        $response = $this->actingAs($this->admin)->get('/products?search=Lenovo');
        $response->assertSee('Laptop Lenovo');
        $response->assertDontSee('Buku Tulis');

        // Search by SKU
        $response = $this->actingAs($this->admin)->get('/products?search=BKU-001');
        $response->assertSee('Buku Tulis');
        $response->assertDontSee('Laptop Lenovo');

        // Filter by Category
        $response = $this->actingAs($this->admin)->get('/products?category_id=' . $cat1->id);
        $response->assertSee('Laptop Lenovo');
        $response->assertDontSee('Buku Tulis');
    }

    /**
     * 7. Test Dashboard Laporan: Aggregate Functions.
     */
    public function test_dashboard_aggregates(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Keyboard',
            'sku' => 'KBD-001',
            'price' => 100000,
            'stock' => 15,
            'min_stock' => 5,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $this->admin->id,
            'type' => 'in',
            'quantity' => 20,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $this->staff->id,
            'type' => 'out',
            'quantity' => 5,
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Nilai Inventory');
        $response->assertSee('Barang Masuk (In)');
        $response->assertSee('Barang Keluar (Out)');
    }

    /**
     * 8. Test Business Logic & Database Transaction: Barang Masuk menambah stok.
     */
    public function test_stock_in_increases_stock_and_creates_movement(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Monitor',
            'sku' => 'MON-001',
            'price' => 2000000,
            'stock' => 10,
            'min_stock' => 3,
        ]);

        $response = $this->actingAs($this->staff)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 5,
            'notes' => 'Penerimaan restock',
        ]);

        $response->assertRedirect('/stock-movements');
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals(15, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 5,
            'notes' => 'Penerimaan restock',
        ]);
    }

    /**
     * 9. Test Business Logic: Stok Tidak Boleh Negatif (Barang keluar melebihi stok ditolak).
     */
    public function test_stock_cannot_be_negative(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Printer',
            'sku' => 'PRT-001',
            'price' => 2500000,
            'stock' => 5,
            'min_stock' => 2,
        ]);

        // Coba keluarkan 10 unit padahal stok hanya 5
        $response = $this->actingAs($this->staff)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 10,
            'notes' => 'Pengeluaran melebihi stok',
        ]);

        $response->assertSessionHasErrors('quantity');

        // Pastikan stok tidak berubah dan transaksi tidak tercatat di database
        $product->refresh();
        $this->assertEquals(5, $product->stock);
        $this->assertDatabaseMissing('stock_movements', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);
    }

    /**
     * 10. Test Cegah Penghapusan Sembarangan: Produk dengan riwayat transaksi tidak boleh dihapus.
     */
    public function test_product_with_stock_movements_cannot_be_deleted(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Scanner',
            'sku' => 'SCN-001',
            'price' => 1200000,
            'stock' => 4,
            'min_stock' => 1,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $this->admin->id,
            'type' => 'in',
            'quantity' => 4,
        ]);

        // Coba hapus produk
        $response = $this->actingAs($this->admin)->delete("/products/{$product->id}");

        $response->assertSessionHas('error');

        // Pastikan produk tetap ada di database
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * 11. Test Validasi Input: SKU harus unik & Harga/Kuantitas tidak boleh negatif.
     */
    public function test_validation_rules(): void
    {
        Product::create([
            'category_id' => $this->category->id,
            'name' => 'Headset',
            'sku' => 'HDS-001',
            'price' => 300000,
            'stock' => 10,
            'min_stock' => 2,
        ]);

        // SKU Duplikat
        $response = $this->actingAs($this->admin)->post('/products', [
            'category_id' => $this->category->id,
            'name' => 'Headset Lain',
            'sku' => 'HDS-001',
            'price' => 350000,
            'stock' => 5,
            'min_stock' => 2,
        ]);
        $response->assertSessionHasErrors('sku');

        // Harga negatif
        $response = $this->actingAs($this->admin)->post('/products', [
            'category_id' => $this->category->id,
            'name' => 'Webcam',
            'sku' => 'WBC-001',
            'price' => -50000,
            'stock' => 5,
            'min_stock' => 2,
        ]);
        $response->assertSessionHasErrors('price');

        // Kuantitas mutasi negatif
        $product = Product::first();
        $response = $this->actingAs($this->staff)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => -5,
        ]);
        $response->assertSessionHasErrors('quantity');
    }

    /**
     * 12. Test One-to-One Relationship (User ↔ UserProfile).
     */
    public function test_user_profile_one_to_one_relationship(): void
    {
        $profile = \App\Models\UserProfile::create([
            'user_id' => $this->admin->id,
            'full_name' => 'Super Administrator',
            'department' => 'IT Infrastructure',
        ]);

        $this->assertEquals($profile->id, $this->admin->profile->id);
        $this->assertEquals($this->admin->id, $profile->user->id);
    }

    /**
     * 13. Test Halaman Laporan Mutasi & Agregat (P4 Slide 14 & 28).
     */
    public function test_inventory_reports_page_accessible_and_renders(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan Ringkasan Kategori');
        $response->assertSee('Laporan Rekapitulasi Mutasi Stok per Produk');
    }

    /**
     * 14. Test Halaman Khusus Stok Kritis (dipindahkan ke menu sidebar).
     */
    public function test_low_stock_page_accessible_and_renders(): void
    {
        $response = $this->actingAs($this->admin)->get('/products/low-stock');
        $response->assertStatus(200);
        $response->assertSee('Monitoring Stok Kritis');
    }

    /**
     * 15. Test Endpoint Realtime Dashboard Charts (AJAX JSON).
     */
    public function test_dashboard_realtime_data_endpoint(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard/realtime-data');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'kpis' => [
                'total_products',
                'total_inventory_value',
                'total_stock_in',
                'total_stock_out',
                'low_stock_count',
            ],
            'chart_trends',
            'chart_categories',
            'chart_top_products',
        ]);
    }
}
