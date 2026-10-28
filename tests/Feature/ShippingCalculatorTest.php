<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ShippingCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => 'pembeli',
        ]);

        $category = Category::create([
            'name' => 'Mouse Gaming',
            'slug' => 'mouse-gaming',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Razer DeathAdder Gaming Mouse',
            'sku' => 'MOU-RZ-001',
            'price' => 500000,
            'selling_price' => 500000,
            'stock' => 20,
            'min_stock' => 5,
        ]);
    }

    public function test_can_fetch_shipping_rates_via_ajax(): void
    {
        $response = $this->actingAs($this->customer)
            ->withSession(['cart' => [$this->product->id => 2]])
            ->getJson(route('customer.cart.shipping-rates', ['destination' => 'surabaya']));

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'rates' => [
                    [
                        'code' => 'jne_reg',
                        'service_name' => 'JNE Reguler',
                    ],
                ],
            ]);

        $rates = $response->json('rates');
        $this->assertNotEmpty($rates);
        $this->assertGreaterThan(0, $rates[0]['cost']);
    }

    public function test_checkout_calculates_and_persists_shipping_cost_correctly(): void
    {
        $mockMidtrans = Mockery::mock(MidtransService::class);
        $mockMidtrans->shouldReceive('createSnapToken')->andReturn('snap-token-test-12345');
        $this->app->instance(MidtransService::class, $mockMidtrans);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.checkout'), [
                'destination' => 'bandung',
                'courier' => 'jnt_ez',
                'payment_method' => 'cod',
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity' => 2,
                    ],
                ],
            ]);

        $sale = Sale::latest('id')->first();
        $this->assertNotNull($sale);
        $this->assertEquals(1000000, $sale->subtotal);
        $this->assertGreaterThan(0, $sale->shipping_cost);
        $this->assertEquals('J&T EZ Express', $sale->shipping_courier);
        $this->assertEquals('Kota Bandung, Jawa Barat', $sale->shipping_destination);
        $this->assertEquals($sale->subtotal + $sale->shipping_cost, $sale->total);

        $response->assertRedirect(route('customer.orders.show', $sale));
    }

    public function test_order_show_displays_shipping_breakdown(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-20261028-0999',
            'subtotal' => 500000,
            'discount_amount' => 0,
            'shipping_cost' => 18000,
            'shipping_courier' => 'JNE Reguler',
            'shipping_destination' => 'Surabaya',
            'shipping_weight' => 500,
            'total' => 518000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'payment_method' => 'cod',
            'shipping_status' => 'packing',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.orders.show', $sale));

        $response->assertOk();
        $response->assertSee('JNE Reguler');
        $response->assertSee('18.000');
    }

    public function test_invoice_pdf_contains_shipping_courier_and_cost(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-20261028-0998',
            'subtotal' => 500000,
            'discount_amount' => 0,
            'shipping_cost' => 25000,
            'shipping_courier' => 'SiCepat BEST (Next Day)',
            'shipping_destination' => 'Denpasar, Bali',
            'shipping_weight' => 1000,
            'total' => 525000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'shipped',
            'sold_at' => now(),
        ]);

        $sale->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => 500000,
            'subtotal' => 500000,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.orders.invoice.pdf', $sale));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
