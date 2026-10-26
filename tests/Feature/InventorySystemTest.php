<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $staff;

    protected User $pembeli;

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

        $this->pembeli = User::factory()->create([
            'role' => 'pembeli',
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
        $response = $this->actingAs($this->admin)->get('/products?category_id='.$cat1->id);
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
     * 10. Test Soft Delete: Produk dengan riwayat transaksi dapat di-soft delete dan disembunyikan.
     */
    public function test_product_with_stock_movements_can_be_soft_deleted(): void
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

        // Hapus produk
        $response = $this->actingAs($this->admin)->delete("/products/{$product->id}");

        $response->assertRedirect('/products');
        $response->assertSessionHas('success');

        // Pastikan produk berstatus soft deleted di database
        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);

        // Pastikan produk tersembunyi dari query produk aktif admin & staff
        $this->assertFalse(Product::where('id', $product->id)->exists());

        // Pastikan produk tersembunyi dari katalog pembeli
        $this->assertFalse(Product::availableForBuyer()->where('id', $product->id)->exists());
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
        $profile = UserProfile::create([
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
                'low_stock_count',
            ],
            'chart_trends',
            'chart_categories',
            'chart_top_products',
        ]);
    }

    /**
     * 16. Test Purchases: Pembelian berhasil menambah stok produk dan mencatat item.
     */
    public function test_purchase_transaction_increases_stock_and_creates_items(): void
    {
        $supplier = Supplier::factory()->create();
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Laptop Gaming ROG',
            'sku' => 'ROG-001',
            'purchase_price' => 15000000,
            'selling_price' => 18000000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);

        $response = $this->actingAs($this->staff)->post('/purchases', [
            'supplier_id' => $supplier->id,
            'notes' => 'Restock ROG',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'unit_cost' => 15000000,
                ],
            ],
        ]);

        $response->assertRedirect();
        $product->refresh();

        // Stok 5 + 3 = 8
        $this->assertEquals(8, $product->stock);

        $this->assertDatabaseHas('purchases', [
            'supplier_id' => $supplier->id,
            'user_id' => $this->staff->id,
            'total' => 45000000,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('purchase_items', [
            'product_id' => $product->id,
            'quantity' => 3,
            'unit_cost' => 15000000,
            'subtotal' => 45000000,
        ]);
    }

    /**
     * 17. Test Sales: Penjualan berhasil mengurangi stok dan mencatat item secara atomik.
     */
    public function test_sale_transaction_decreases_stock_and_creates_items(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Mouse Gaming Razer',
            'sku' => 'RZR-001',
            'purchase_price' => 400000,
            'selling_price' => 600000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        $response = $this->actingAs($this->staff)->post('/sales', [
            'buyer_id' => $this->pembeli->id,
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 4,
                ],
            ],
        ]);

        $response->assertRedirect();
        $product->refresh();

        // Stok 10 - 4 = 6
        $this->assertEquals(6, $product->stock);

        $this->assertDatabaseHas('sales', [
            'user_id' => $this->staff->id,
            'buyer_id' => $this->pembeli->id,
            'total' => 2400000,
            'status' => 'completed',
        ]);
    }

    /**
     * 18. Test Sales: Penjualan ditolak jika stok tidak mencukupi (Anti-Overselling).
     */
    public function test_sale_insufficient_stock_is_rejected_and_data_unchanged(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Monitor Gaming 240Hz',
            'sku' => 'MON-240',
            'purchase_price' => 3000000,
            'selling_price' => 4000000,
            'stock' => 2,
            'minimum_stock' => 1,
        ]);

        // Coba beli 5 unit (stok cuma 2)
        $response = $this->actingAs($this->staff)->post('/sales', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $product->refresh();

        // Stok harus tetap utuh (2) dan tidak ada transaksi tersimpan
        $this->assertEquals(2, $product->stock);
        $this->assertDatabaseMissing('sales', [
            'total' => 20000000,
        ]);
    }

    /**
     * 19. Test Atomicity: Jika 1 item dalam multi-item gagal, seluruh transaksi rollback.
     */
    public function test_sale_multi_item_atomicity_rolls_back_if_one_item_fails(): void
    {
        $prodA = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Produk A',
            'sku' => 'PRD-A',
            'selling_price' => 100000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        $prodB = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Produk B',
            'sku' => 'PRD-B',
            'selling_price' => 200000,
            'stock' => 2, // Stok hanya 2
            'minimum_stock' => 1,
        ]);

        // Transaksi beli 3 unit Prod A (cukup) tapi 5 unit Prod B (kurang)
        $response = $this->actingAs($this->staff)->post('/sales', [
            'items' => [
                ['product_id' => $prodA->id, 'quantity' => 3],
                ['product_id' => $prodB->id, 'quantity' => 5],
            ],
        ]);

        $response->assertSessionHasErrors('items');

        $prodA->refresh();
        $prodB->refresh();

        // Pastikan Prod A TIDAK berkurang karena rollback
        $this->assertEquals(10, $prodA->stock);
        $this->assertEquals(2, $prodB->stock);
        $this->assertDatabaseCount('sales', 0);
    }

    /**
     * 20. Test Pembatalan Transaksi oleh Admin: Memulihkan stok secara akurat.
     */
    public function test_admin_can_cancel_sale_and_stock_is_restored(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Webcam 4K',
            'sku' => 'WBC-4K',
            'selling_price' => 1000000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        // Buat sale (stok jadi 7)
        $this->actingAs($this->staff)->post('/sales', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ]);

        $product->refresh();
        $this->assertEquals(7, $product->stock);

        $sale = Sale::latest()->first();

        // Admin membatalkan transaksi
        $cancelResponse = $this->actingAs($this->admin)->post("/sales/{$sale->id}/cancel");
        $cancelResponse->assertRedirect();

        $product->refresh();
        $sale->refresh();

        // Stok harus pulih kembali menjadi 10
        $this->assertEquals(10, $product->stock);
        $this->assertEquals('cancelled', $sale->status);
    }

    /**
     * 21. Test Otorisasi 3 Role: Staff & Pembeli dilarang membatalkan transaksi.
     */
    public function test_staff_and_pembeli_cannot_cancel_transactions(): void
    {
        $sale = Sale::create([
            'user_id' => $this->staff->id,
            'sale_number' => 'SL-TEST-001',
            'total' => 100000,
            'status' => 'completed',
        ]);

        // Staff ditolak 403
        $respStaff = $this->actingAs($this->staff)->post("/sales/{$sale->id}/cancel");
        $respStaff->assertStatus(403);

        // Pembeli ditolak 403
        $respPembeli = $this->actingAs($this->pembeli)->post("/sales/{$sale->id}/cancel");
        $respPembeli->assertStatus(403);
    }

    /**
     * 22. Test Otorisasi 3 Role: Pembeli dilarang mengakses modul Admin & Staff.
     */
    public function test_pembeli_cannot_access_admin_or_staff_routes(): void
    {
        // Akses admin master
        $response = $this->actingAs($this->pembeli)->get('/users');
        $response->assertStatus(403);
        $response->assertSee('Kembali ke Beranda');
        $response->assertDontSee('Dashboard Saya');
        $response->assertDontSee('(Role Admin)');

        $this->actingAs($this->pembeli)->get('/categories')->assertStatus(403);
        $this->actingAs($this->pembeli)->get('/suppliers')->assertStatus(403);

        // Akses modul operasional staff
        $this->actingAs($this->pembeli)->get('/purchases')->assertStatus(403);
        $this->actingAs($this->pembeli)->get('/sales')->assertStatus(403);
        $this->actingAs($this->pembeli)->get('/stock-movements')->assertStatus(403);
    }

    /**
     * 23. Test Pembeli: Dapat melihat katalog dan checkout dengan token Midtrans Snap.
     */
    public function test_pembeli_can_browse_catalog_and_checkout_with_snap_token(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Headset Bluetooth',
            'sku' => 'HDS-BT-001',
            'selling_price' => 500000,
            'stock' => 8,
            'minimum_stock' => 2,
        ]);

        // Buka katalog pembeli
        $response = $this->actingAs($this->pembeli)->get('/catalog');
        $response->assertStatus(200);
        $response->assertSee('Headset Bluetooth');

        // Checkout barang
        $checkoutResponse = $this->actingAs($this->pembeli)->post('/cart/checkout', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
            'notes' => 'Tolong bubble wrap',
        ]);

        $checkoutResponse->assertRedirect();
        $product->refresh();

        // Stok terpotong 8 - 2 = 6
        $this->assertEquals(6, $product->stock);

        $sale = Sale::latest()->first();
        $this->assertNotNull($sale);
        $this->assertNotEmpty($sale->snap_token);
        $this->assertEquals('pending', $sale->payment_status);
        $this->assertEquals($this->pembeli->id, $sale->buyer_id);
    }

    /**
     * 24. Test Otorisasi Pembeli: Pembeli dilarang melihat pesanan milik pembeli lain.
     */
    public function test_pembeli_cannot_view_other_buyers_orders(): void
    {
        $otherBuyer = User::factory()->create(['role' => 'pembeli']);

        $otherSale = Sale::create([
            'user_id' => $otherBuyer->id,
            'buyer_id' => $otherBuyer->id,
            'sale_number' => 'SL-OTHER-001',
            'total' => 250000,
            'status' => 'completed',
        ]);

        // Pembeli pertama coba lihat invoice pembeli lain via direct URL
        $response = $this->actingAs($this->pembeli)->get("/my-orders/{$otherSale->id}");
        $response->assertStatus(403);
    }

    /**
     * 25. Test Landing Page Publik (REDMAGIC Theme) dapat diakses oleh guest.
     */
    public function test_landing_page_is_publicly_accessible(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'REDMAGIC 9 Pro Gaming Phone',
            'stock' => 10,
            'purchase_price' => 10000000,
            'selling_price' => 13500000,
            'minimum_stock' => 2,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('REDMAGIC');
        $response->assertSee('REDMAGIC 9 Pro Gaming Phone');
    }

    /**
     * 26. Test Klik Produk pada Landing Page: Tamu (Guest) dialihkan ke form login.
     */
    public function test_guest_clicking_product_is_redirected_to_login_form(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'REDMAGIC Cyber Mechanical Keyboard',
            'stock' => 5,
            'purchase_price' => 1200000,
            'selling_price' => 1750000,
            'minimum_stock' => 1,
        ]);

        $response = $this->get(route('product.preview', $product->id));
        $response->assertRedirect(route('login', [
            'intended' => route('customer.catalog.show', $product),
            'product' => $product->name,
        ]));
        $response->assertSessionHas('info');
    }

    /**
     * 27. Test Klik Produk oleh Pembeli terautentikasi: langsung diarahkan ke detail katalog.
     */
    public function test_authenticated_buyer_clicking_product_redirects_to_catalog_show(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'REDMAGIC Gaming Mouse',
            'stock' => 8,
            'purchase_price' => 450000,
            'selling_price' => 750000,
            'minimum_stock' => 2,
        ]);

        $response = $this->actingAs($this->pembeli)->get(route('product.preview', $product->id));
        $response->assertRedirect(route('customer.catalog.show', $product));
    }

    /**
     * 28. Test Registrasi Pengguna Baru dengan pilihan Role.
     */
    public function test_user_can_register_with_role_selection(): void
    {
        $response = $this->post(route('register.attempt'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'pembeli',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'budi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('pembeli', $user->role);
    }

    /**
     * 29. Test Registrasi gagal jika email sudah terdaftar atau sandi tidak cocok.
     */
    public function test_registration_validation(): void
    {
        $response = $this->post(route('register.attempt'), [
            'name' => 'Admin Copy',
            'email' => $this->admin->email, // duplicate
            'role' => 'pembeli',
            'password' => 'secret123',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    /**
     * 30. Test Alur Forgot Password & Reset Password.
     */
    public function test_forgot_and_reset_password_flow(): void
    {
        // 1. Request forgot password link
        $response = $this->post(route('password.email'), [
            'email' => $this->pembeli->email,
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $this->pembeli->email,
        ]);

        // 2. Fetch created token
        $record = DB::table('password_reset_tokens')
            ->where('email', $this->pembeli->email)
            ->first();
        $this->assertNotNull($record);

        // 3. Reset password request
        $token = 'random-token-string';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->pembeli->email],
            ['token' => Hash::make($token)]
        );

        $resetResponse = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $this->pembeli->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $resetResponse->assertRedirect(route('login'));
        $resetResponse->assertSessionHas('success');

        // Verify new password can login
        $this->assertTrue(Auth::attempt([
            'email' => $this->pembeli->email,
            'password' => 'newpassword123',
        ]));
    }

    /**
     * 31. Test Simulasi Single Sign-On (Google SSO).
     */
    public function test_social_sso_login_simulation(): void
    {
        $response = $this->get(route('auth.social', 'google'));
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('pembeli', Auth::user()->role);
    }

    /**
     * 32. Test Upload, Ganti, dan Hapus Foto Produk oleh Admin.
     */
    public function test_admin_can_upload_product_photo_on_create_and_update(): void
    {
        $fakeImage = UploadedFile::fake()->image('gaming-laptop.png', 300, 300);

        $response = $this->actingAs($this->admin)->post('/products', [
            'category_id' => $this->category->id,
            'sku' => 'LAP-001',
            'name' => 'Laptop Gaming Titan',
            'description' => 'Laptop spek tinggi',
            'price' => 15000000,
            'stock' => 10,
            'min_stock' => 2,
            'image' => $fakeImage,
        ]);

        $response->assertRedirect('/products');
        $product = Product::where('sku', 'LAP-001')->firstOrFail();

        $this->assertNotNull($product->image);
        $this->assertFileExists(public_path($product->image));
        $this->assertNotNull($product->image_url);

        // Update: Ganti dengan foto baru
        $newFakeImage = UploadedFile::fake()->image('gaming-laptop-v2.png', 400, 400);
        $oldImagePath = public_path($product->image);

        $updateResponse = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'category_id' => $this->category->id,
            'sku' => 'LAP-001',
            'name' => 'Laptop Gaming Titan V2',
            'price' => 16000000,
            'min_stock' => 2,
            'image' => $newFakeImage,
        ]);

        $updateResponse->assertRedirect('/products');
        $product->refresh();

        $this->assertFileDoesNotExist($oldImagePath);
        $this->assertFileExists(public_path($product->image));

        // Update: Hapus foto
        $currentImagePath = public_path($product->image);
        $deleteImageResponse = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'category_id' => $this->category->id,
            'sku' => 'LAP-001',
            'name' => 'Laptop Gaming Titan V2',
            'price' => 16000000,
            'min_stock' => 2,
            'remove_image' => '1',
        ]);

        $deleteImageResponse->assertRedirect('/products');
        $product->refresh();

        $this->assertNull($product->image);
        $this->assertNull($product->image_url);
        $this->assertFileDoesNotExist($currentImagePath);
    }

    /**
     * Test Logout: Saat user keluar (admin, staff, pembeli), otomatis diarahkan ke landing page.
     */
    public function test_logout_redirects_to_landing_page_for_all_roles(): void
    {
        // 1. Admin Logout
        $adminResponse = $this->actingAs($this->admin)->post('/logout');
        $adminResponse->assertRedirect('/');
        $this->assertGuest();

        // 2. Staff Logout
        $staffResponse = $this->actingAs($this->staff)->post('/logout');
        $staffResponse->assertRedirect('/');
        $this->assertGuest();

        // 3. Buyer Logout
        $buyerResponse = $this->actingAs($this->pembeli)->post('/logout');
        $buyerResponse->assertRedirect('/');
        $this->assertGuest();
    }
}
