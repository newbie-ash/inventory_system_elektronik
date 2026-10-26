<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CustomerActivity;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerActivityAndOrdersTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => 'pembeli',
            'email' => 'buyer@example.com',
        ]);

        $category = Category::create([
            'name' => 'Gaming Gear',
            'description' => 'Aksesoris gaming',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Mechanical Keyboard RGB',
            'sku' => 'KB-001',
            'cost_price' => 200000,
            'selling_price' => 350000,
            'price' => 350000,
            'stock' => 10,
            'min_stock' => 2,
        ]);
    }

    public function test_customer_viewing_product_records_activity(): void
    {
        $this->actingAs($this->customer)
            ->get(route('customer.catalog.show', $this->product))
            ->assertStatus(200);

        $this->assertDatabaseHas('customer_activities', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'view_count' => 1,
        ]);

        // Viewing again increments view count
        $this->actingAs($this->customer)
            ->get(route('customer.catalog.show', $this->product))
            ->assertStatus(200);

        $this->assertDatabaseHas('customer_activities', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'view_count' => 2,
        ]);
    }

    public function test_customer_can_view_activity_page_with_grouped_items(): void
    {
        CustomerActivity::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'view_count' => 3,
            'last_viewed_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.activity'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Aktivitas');
        $response->assertSee('Mechanical Keyboard RGB');
        $response->assertSee('Anda melihat 3 kali');
    }

    public function test_customer_can_delete_single_activity(): void
    {
        $activity = CustomerActivity::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'view_count' => 1,
            'last_viewed_at' => now(),
        ]);

        $this->actingAs($this->customer)
            ->delete(route('customer.activity.destroy', $activity))
            ->assertRedirect(route('customer.activity'));

        $this->assertDatabaseMissing('customer_activities', [
            'id' => $activity->id,
        ]);
    }

    public function test_customer_can_clear_all_activities(): void
    {
        CustomerActivity::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'view_count' => 1,
            'last_viewed_at' => now(),
        ]);

        $this->actingAs($this->customer)
            ->delete(route('customer.activity.clear'))
            ->assertRedirect(route('customer.activity'));

        $this->assertDatabaseEmpty('customer_activities');
    }

    public function test_customer_orders_tabs_filter_properly(): void
    {
        // 1. Unpaid order
        $unpaidOrder = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-UNPAID-001',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'payment_method' => 'midtrans',
            'shipping_status' => 'pending',
            'sold_at' => now(),
        ]);

        // 2. Packing order (COD or Paid)
        $packingOrder = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-PACKING-001',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'packing',
            'sold_at' => now(),
        ]);

        // 3. Shipped order
        $shippedOrder = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-SHIPPED-001',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'shipped',
            'sold_at' => now(),
        ]);

        // Unpaid tab check
        $resUnpaid = $this->actingAs($this->customer)
            ->get(route('customer.orders.index', ['tab' => 'unpaid']));
        $resUnpaid->assertStatus(200);
        $resUnpaid->assertSee('SL-UNPAID-001');
        $resUnpaid->assertDontSee('SL-SHIPPED-001');

        // Packing tab check
        $resPacking = $this->actingAs($this->customer)
            ->get(route('customer.orders.index', ['tab' => 'packing']));
        $resPacking->assertStatus(200);
        $resPacking->assertSee('SL-PACKING-001');
        $resPacking->assertDontSee('SL-UNPAID-001');

        // Shipping tab check
        $resShipping = $this->actingAs($this->customer)
            ->get(route('customer.orders.index', ['tab' => 'shipping']));
        $resShipping->assertStatus(200);
        $resShipping->assertSee('SL-SHIPPED-001');
        $resShipping->assertDontSee('SL-PACKING-001');
    }

    public function test_customer_can_confirm_delivery_and_reorder(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-DELIVER-001',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'shipped',
            'sold_at' => now(),
        ]);

        $sale->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 350000,
            'subtotal' => 700000,
        ]);

        // Confirm delivery
        $this->actingAs($this->customer)
            ->post(route('customer.orders.confirm-delivery', $sale))
            ->assertRedirect();

        $this->assertEquals('delivered', $sale->fresh()->shipping_status);

        // Reorder into cart
        $this->actingAs($this->customer)
            ->post(route('customer.cart.reorder', $sale))
            ->assertRedirect(route('customer.cart'));

        $cart = session('cart');
        $this->assertEquals(2, $cart[$this->product->id]);
    }

    public function test_staff_and_admin_can_update_shipping_status(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'email' => 'staff@example.com',
        ]);

        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-FULFILL-001',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'packing',
            'sold_at' => now(),
        ]);

        // Staff dispatches package to courier
        $this->actingAs($staff)
            ->patch(route('sales.update-shipping-status', $sale), [
                'shipping_status' => 'shipped',
            ])
            ->assertRedirect();

        $this->assertEquals('shipped', $sale->fresh()->shipping_status);

        // Buyer cannot access staff shipping status update route (403 forbidden)
        $this->actingAs($this->customer)
            ->patch(route('sales.update-shipping-status', $sale), [
                'shipping_status' => 'delivered',
            ])
            ->assertStatus(403);
    }

    public function test_customer_can_cancel_unpaid_order_and_stock_is_restored(): void
    {
        $initialStock = $this->product->stock; // 10

        // Create an order for 3 units
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-CANCEL-001',
            'subtotal' => 1050000,
            'total' => 1050000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'payment_method' => 'midtrans',
            'shipping_status' => 'pending',
            'sold_at' => now(),
        ]);

        $sale->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 3,
            'unit_price' => 350000,
            'subtotal' => 1050000,
        ]);
        $this->product->decrement('stock', 3);
        $this->assertEquals($initialStock - 3, $this->product->fresh()->stock);

        // Customer cancels order
        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.cancel', $sale));

        $response->assertRedirect(route('customer.orders.index', ['tab' => 'cancelled']));
        $response->assertSessionHas('success');

        $sale->refresh();
        $this->assertEquals('cancelled', $sale->status);
        $this->assertEquals('failed', $sale->payment_status);

        // Product stock must be restored
        $this->assertEquals($initialStock, $this->product->fresh()->stock);

        // StockMovement recorded
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 3,
        ]);
    }

    public function test_customer_cannot_cancel_shipped_order(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-SHIPPED-CANCEL',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'shipped',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.cancel', $sale));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals('completed', $sale->fresh()->status);
    }

    public function test_customer_can_switch_unpaid_order_to_cod(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-SWITCH-COD',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'payment_method' => 'midtrans',
            'shipping_status' => 'pending',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.switch-to-cod', $sale));

        $response->assertRedirect(route('customer.orders.show', $sale));
        $response->assertSessionHas('success');

        $sale->refresh();
        $this->assertEquals('cod', $sale->payment_method);
        $this->assertEquals('packing', $sale->shipping_status);
    }

    public function test_cod_order_sets_payment_status_to_paid_on_delivery(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-COD-DELIVER',
            'subtotal' => 350000,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'payment_method' => 'cod',
            'shipping_status' => 'shipped',
            'sold_at' => now(),
        ]);

        $this->actingAs($this->customer)
            ->post(route('customer.orders.confirm-delivery', $sale))
            ->assertRedirect();

        $sale->refresh();
        $this->assertEquals('delivered', $sale->shipping_status);
        $this->assertEquals('paid', $sale->payment_status);
    }

    public function test_customer_can_create_direct_order_with_promo_code(): void
    {
        $response = $this->actingAs($this->customer)
            ->post(route('customer.checkout'), [
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity' => 1, // product price is 350.000, eligible for GEAR50K (min 300.000)
                    ],
                ],
                'promo_code' => 'GEAR50K',
                'payment_method' => 'cod',
            ]);

        $sale = Sale::where('buyer_id', $this->customer->id)->latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals('GEAR50K', $sale->promo_code);
        $this->assertEquals(50000, $sale->discount_amount);
        $this->assertEquals(350000, $sale->subtotal);
        $this->assertEquals(300000, $sale->total);
    }

    public function test_customer_can_apply_promo_code_on_payment_page(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-APPLY-PROMO',
            'subtotal' => 350000,
            'discount_amount' => 0,
            'total' => 350000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'payment_method' => 'midtrans',
            'shipping_status' => 'pending',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.apply-promo', $sale), [
                'promo_code' => 'GEAR50K',
            ]);

        $response->assertRedirect(route('customer.orders.payment', $sale));
        $response->assertSessionHas('success');

        $sale->refresh();
        $this->assertEquals('GEAR50K', $sale->promo_code);
        $this->assertEquals(50000, $sale->discount_amount);
        $this->assertEquals(300000, $sale->total);
    }
}
