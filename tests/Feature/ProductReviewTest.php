<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected Product $product;

    protected Sale $deliveredSale;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => 'pembeli',
            'email' => 'reviewer@example.com',
        ]);

        $category = Category::create([
            'name' => 'Monitors',
            'description' => 'Gaming Displays',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gaming Monitor 240Hz',
            'sku' => 'MON-240',
            'cost_price' => 2500000,
            'selling_price' => 3800000,
            'price' => 3800000,
            'stock' => 5,
            'min_stock' => 1,
        ]);

        $this->deliveredSale = Sale::create([
            'user_id' => $this->customer->id,
            'buyer_id' => $this->customer->id,
            'sale_number' => 'SL-REV-001',
            'subtotal' => 3800000,
            'total' => 3800000,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'midtrans',
            'shipping_status' => 'delivered',
            'sold_at' => now(),
        ]);

        $this->deliveredSale->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => 3800000,
            'subtotal' => 3800000,
        ]);
    }

    public function test_customer_can_submit_review_for_delivered_product(): void
    {
        $response = $this->actingAs($this->customer)
            ->post(route('customer.reviews.store'), [
                'product_id' => $this->product->id,
                'sale_id' => $this->deliveredSale->id,
                'rating' => 5,
                'comment' => 'Layar luar biasa jernih, refresh rate 240Hz mulus tanpa ghosting.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'sale_id' => $this->deliveredSale->id,
            'rating' => 5,
            'comment' => 'Layar luar biasa jernih, refresh rate 240Hz mulus tanpa ghosting.',
        ]);

        $this->assertEquals(5.0, $this->product->fresh()->averageRating());
        $this->assertEquals(1, $this->product->fresh()->reviewsCount());
    }

    public function test_rating_must_be_between_one_and_five(): void
    {
        $response = $this->actingAs($this->customer)
            ->post(route('customer.reviews.store'), [
                'product_id' => $this->product->id,
                'sale_id' => $this->deliveredSale->id,
                'rating' => 6,
                'comment' => 'Too high',
            ]);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseEmpty('reviews');
    }

    public function test_customer_cannot_review_unpurchased_product(): void
    {
        $otherCustomer = User::factory()->create([
            'role' => 'pembeli',
            'email' => 'other@example.com',
        ]);

        $response = $this->actingAs($otherCustomer)
            ->post(route('customer.reviews.store'), [
                'product_id' => $this->product->id,
                'rating' => 4,
                'comment' => 'I have never bought this',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseEmpty('reviews');
    }

    public function test_customer_can_update_existing_review(): void
    {
        Review::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'sale_id' => $this->deliveredSale->id,
            'rating' => 4,
            'comment' => 'Awalnya bagus',
        ]);

        $this->actingAs($this->customer)
            ->post(route('customer.reviews.store'), [
                'product_id' => $this->product->id,
                'sale_id' => $this->deliveredSale->id,
                'rating' => 5,
                'comment' => 'Setelah dipakai 1 minggu, sangat memuaskan!',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseHas('reviews', [
            'rating' => 5,
            'comment' => 'Setelah dipakai 1 minggu, sangat memuaskan!',
        ]);
    }

    public function test_product_detail_displays_reviews_and_average_rating(): void
    {
        Review::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'sale_id' => $this->deliveredSale->id,
            'rating' => 5,
            'comment' => 'Produk juara kualitas tinggi',
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.catalog.show', $this->product));

        $response->assertStatus(200);
        $response->assertSee('5.0');
        $response->assertSee('1 Ulasan Pembeli');
        $response->assertSee('Produk juara kualitas tinggi');
    }
}
