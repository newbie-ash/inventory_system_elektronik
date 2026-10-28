<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $product;

    protected string $serverKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'pembeli']);
        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'price' => 500000,
            'selling_price' => 500000,
        ]);

        $this->serverKey = config('services.midtrans.server_key', 'SB-Mid-server-test-demo-key');
    }

    protected function generateSignature(string $orderId, string $statusCode, string $grossAmount): string
    {
        return hash('sha512', $orderId.$statusCode.$grossAmount.$this->serverKey);
    }

    public function test_webhook_rejects_missing_payload(): void
    {
        $response = $this->postJson('/payments/midtrans-notification', []);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Invalid notification payload.']);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $response = $this->postJson('/payments/midtrans-notification', [
            'order_id' => 'INV-TEST-0001',
            'status_code' => '200',
            'gross_amount' => '500000.00',
            'signature_key' => 'invalid_bogus_signature_hash',
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'Invalid signature key.']);
    }

    public function test_webhook_returns_404_when_sale_not_found(): void
    {
        $orderId = 'NON-EXISTENT-ORDER-999';
        $statusCode = '200';
        $grossAmount = '500000.00';
        $signature = $this->generateSignature($orderId, $statusCode, $grossAmount);

        $response = $this->postJson('/payments/midtrans-notification', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Transaction not found.']);
    }

    public function test_webhook_successfully_settles_payment_and_advances_shipping(): void
    {
        $sale = Sale::create([
            'sale_number' => 'SL-TEST-20261028-001',
            'user_id' => $this->user->id,
            'buyer_id' => $this->user->id,
            'total' => 500000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'payment_method' => 'midtrans',
            'snap_token' => 'SNAP-SAMPLE-TOKEN',
            'sold_at' => now(),
        ]);

        $orderId = $sale->sale_number;
        $statusCode = '200';
        $grossAmount = '500000.00';
        $signature = $this->generateSignature($orderId, $statusCode, $grossAmount);

        $response = $this->postJson('/payments/midtrans-notification', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'order_id' => $orderId,
            'payment_status' => 'paid',
            'shipping_status' => 'packing',
            'transaction_status' => 'settlement',
        ]);

        $sale->refresh();
        $this->assertEquals('paid', $sale->payment_status);
        $this->assertEquals('packing', $sale->shipping_status);
        $this->assertEquals('midtrans (qris)', $sale->payment_method);
    }

    public function test_webhook_alias_route_works_without_csrf(): void
    {
        $sale = Sale::create([
            'sale_number' => 'SL-TEST-20261028-002',
            'user_id' => $this->user->id,
            'buyer_id' => $this->user->id,
            'total' => 500000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'payment_method' => 'midtrans',
            'snap_token' => 'SNAP-SAMPLE-TOKEN-2',
            'sold_at' => now(),
        ]);

        $orderId = $sale->sale_number;
        $statusCode = '200';
        $grossAmount = '500000.00';
        $signature = $this->generateSignature($orderId, $statusCode, $grossAmount);

        $response = $this->postJson('/api/midtrans/notification', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
        ]);

        $response->assertStatus(200);
        $sale->refresh();
        $this->assertEquals('paid', $sale->payment_status);
    }

    public function test_webhook_cancels_sale_and_restores_stock_on_failure_or_expire(): void
    {
        $sale = Sale::create([
            'sale_number' => 'SL-TEST-20261028-003',
            'user_id' => $this->user->id,
            'buyer_id' => $this->user->id,
            'total' => 1000000,
            'status' => 'completed',
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'payment_method' => 'midtrans',
            'snap_token' => 'SNAP-SAMPLE-TOKEN-3',
            'sold_at' => now(),
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
            'unit_price' => 500000,
            'subtotal' => 1500000,
        ]);

        $this->assertEquals(10, $this->product->fresh()->stock);

        $orderId = $sale->sale_number;
        $statusCode = '202';
        $grossAmount = '1000000.00';
        $signature = $this->generateSignature($orderId, $statusCode, $grossAmount);

        $response = $this->postJson('/payments/midtrans-notification', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'expire',
        ]);

        $response->assertStatus(200);

        $sale->refresh();
        $this->assertEquals('cancelled', $sale->status);
        $this->assertEquals('failed', $sale->payment_status);
        $this->assertEquals('cancelled', $sale->shipping_status);

        // Stock restored from 10 to 13
        $this->assertEquals(13, $this->product->fresh()->stock);

        // Stock movement recorded
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 3,
        ]);
    }
}
