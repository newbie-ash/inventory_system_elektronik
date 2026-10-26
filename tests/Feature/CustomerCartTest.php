<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCartTest extends TestCase
{
    use RefreshDatabase;

    private function createBuyer(): User
    {
        return User::factory()->create([
            'role' => 'pembeli',
            'name' => 'Fikri Pembeli',
            'email' => 'pembeli@example.com',
        ]);
    }

    private function createProduct(int $stock = 10, float $price = 50000, string $sku = 'LOGI-GPRO-01'): Product
    {
        $category = Category::firstOrCreate([
            'name' => 'Hardware',
        ], [
            'description' => 'Komponen Hardware',
        ]);

        return Product::create([
            'name' => 'Logitech G Pro X Superlight '.$sku,
            'sku' => $sku,
            'category_id' => $category->id,
            'price' => $price,
            'selling_price' => $price,
            'stock' => $stock,
            'min_stock' => 2,
            'description' => 'Wireless gaming mouse',
        ]);
    }

    public function test_guest_cannot_access_cart(): void
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    public function test_buyer_can_view_empty_cart(): void
    {
        $buyer = $this->createBuyer();

        $response = $this->actingAs($buyer)->get('/cart');

        $response->assertStatus(200);
        $response->assertSee('Keranjang Belanja Anda Masih Kosong');
        $response->assertSee('Jelajahi Katalog Produk');
    }

    public function test_buyer_can_add_item_to_cart(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10);

        $response = $this->actingAs($buyer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHas('cart', [
            $product->id => 2,
        ]);
        $response->assertSessionHas('success');

        $viewResponse = $this->actingAs($buyer)->get('/cart');
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee($product->name);
        $viewResponse->assertSee('LOGI-GPRO-01');
    }

    public function test_adding_item_with_nested_input_structure_works(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10);

        $response = $this->actingAs($buyer)->post('/cart/add', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertSessionHas('cart', [
            $product->id => 3,
        ]);
        $response->assertSessionHas('success');
    }

    public function test_cannot_add_item_with_zero_stock(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 0);

        $response = $this->actingAs($buyer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('error');
        $this->assertEmpty(session('cart', []));
    }

    public function test_adding_more_than_available_stock_caps_at_stock_and_warns(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 3);

        $response = $this->actingAs($buyer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $response->assertSessionHas('warning');
        $response->assertSessionHas('cart', [
            $product->id => 3,
        ]);
    }

    public function test_buyer_can_update_cart_item_quantity(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10);

        $response = $this->actingAs($buyer)
            ->withSession(['cart' => [$product->id => 2]])
            ->patch('/cart/update', [
                'product_id' => $product->id,
                'quantity' => 5,
            ]);

        $response->assertSessionHas('cart', [
            $product->id => 5,
        ]);
        $response->assertSessionHas('success');
    }

    public function test_buyer_can_remove_item_from_cart(): void
    {
        $buyer = $this->createBuyer();
        $product1 = $this->createProduct(stock: 10, price: 50000, sku: 'SKU-001');
        $product2 = $this->createProduct(stock: 5, price: 75000, sku: 'SKU-002');

        $response = $this->actingAs($buyer)
            ->withSession(['cart' => [
                $product1->id => 2,
                $product2->id => 1,
            ]])
            ->delete("/cart/remove/{$product1->id}");

        $response->assertSessionHas('cart', [
            $product2->id => 1,
        ]);
        $response->assertSessionHas('success');
    }

    public function test_buyer_can_clear_cart(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10);

        $response = $this->actingAs($buyer)
            ->withSession(['cart' => [$product->id => 3]])
            ->delete('/cart/clear');

        $response->assertSessionMissing('cart');
        $response->assertSessionHas('success');
    }

    public function test_successful_checkout_clears_cart_session(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10, price: 50000, sku: 'SKU-CHK-01');

        $response = $this->actingAs($buyer)
            ->withSession(['cart' => [$product->id => 2]])
            ->post(route('customer.checkout'), [
                'items' => [
                    [
                        'product_id' => $product->id,
                        'quantity' => 2,
                    ],
                ],
                'notes' => 'Tolong bubble wrap tebal',
            ]);

        $response->assertSessionMissing('cart');
        $response->assertSessionHas('success');
    }

    public function test_order_show_auto_verifies_paid_status_on_midtrans_redirect(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10, price: 50000, sku: 'SKU-VERIF-01');

        $sale = Sale::create([
            'user_id' => $buyer->id,
            'buyer_id' => $buyer->id,
            'sale_number' => 'SL-TEST-VERIF-01',
            'total' => 50000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'payment_method' => 'midtrans',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($buyer)->get(route('customer.orders.show', [
            'sale' => $sale->id,
            'transaction_status' => 'settlement',
        ]));

        $response->assertStatus(200);
        $this->assertEquals('paid', $sale->fresh()->payment_status);
        $response->assertSee('LUNAS (PAID)');
    }

    public function test_checkout_with_cod_payment_method(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10, price: 50000, sku: 'SKU-COD-01');

        $response = $this->actingAs($buyer)->post(route('customer.checkout'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
            'payment_method' => 'cod',
            'notes' => 'Bayar pas kurir datang ya',
        ]);

        $sale = Sale::where('buyer_id', $buyer->id)->latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals('cod', $sale->payment_method);
        $this->assertEquals('pending', $sale->payment_status);
        $this->assertNull($sale->snap_token);
        $response->assertRedirect(route('customer.orders.show', $sale));

        $showResponse = $this->actingAs($buyer)->get(route('customer.orders.show', $sale));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('COD (BAYAR DI TEMPAT)');
        $showResponse->assertDontSee('id="pay-button"', false);
    }

    public function test_checkout_with_promo_code_applies_discount(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10, price: 400000, sku: 'SKU-PROMO-01');

        $response = $this->actingAs($buyer)->post(route('customer.checkout'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
            'promo_code' => 'GEAR50K',
            'payment_method' => 'cod',
        ]);

        $sale = Sale::where('buyer_id', $buyer->id)->latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals(400000, (float) $sale->subtotal);
        $this->assertEquals(50000, (float) $sale->discount_amount);
        $this->assertEquals(350000, (float) $sale->total);
        $this->assertEquals('GEAR50K', $sale->promo_code);
    }

    public function test_midtrans_checkout_redirects_to_dedicated_payment_page(): void
    {
        $buyer = $this->createBuyer();
        $product = $this->createProduct(stock: 10, price: 100000, sku: 'SKU-PAY-01');

        $response = $this->actingAs($buyer)->post(route('customer.checkout'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
            'payment_method' => 'midtrans',
        ]);

        $sale = Sale::where('buyer_id', $buyer->id)->latest()->first();
        $this->assertNotNull($sale);
        $response->assertRedirect(route('customer.orders.payment', $sale));

        $paymentResponse = $this->actingAs($buyer)->get(route('customer.orders.payment', $sale));
        $paymentResponse->assertStatus(200);
        $paymentResponse->assertSee('Portal Pembayaran Online');
        $paymentResponse->assertSee('id="pay-button"', false);
    }
}
