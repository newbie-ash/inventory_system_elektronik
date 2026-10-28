<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LowStockNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $staff;

    private User $customer;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
        ]);

        $this->staff = User::factory()->create([
            'role' => 'staff',
            'email' => 'staff@test.com',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'pembeli',
            'email' => 'customer@test.com',
        ]);

        $this->category = Category::factory()->create();
    }

    public function test_low_stock_notification_is_sent_to_admin_and_staff_when_stock_drops(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 10,
            'min_stock' => 5,
        ]);

        $this->assertFalse($product->isLowStock());

        // Drop stock to 4 (below min_stock 5)
        $product->update(['stock' => 4]);
        $product->notifyIfLowStock();

        $this->assertTrue($product->isLowStock());

        // Verify admin and staff received notification
        $this->assertEquals(1, $this->admin->unreadNotifications()->count());
        $this->assertEquals(1, $this->staff->unreadNotifications()->count());
        $this->assertEquals(0, $this->customer->unreadNotifications()->count());

        $notificationData = $this->admin->unreadNotifications()->first()->data;
        $this->assertEquals($product->id, $notificationData['product_id']);
        $this->assertEquals($product->name, $notificationData['product_name']);
        $this->assertEquals(4, $notificationData['stock']);
    }

    public function test_duplicate_notification_is_prevented_while_unread(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 3,
            'min_stock' => 5,
        ]);

        // First notification
        $product->notifyIfLowStock();
        $this->assertEquals(1, $this->admin->unreadNotifications()->count());

        // Second notification attempt for same product should not create duplicate
        $product->notifyIfLowStock();
        $this->assertEquals(1, $this->admin->unreadNotifications()->count());
    }

    public function test_sale_creation_triggers_low_stock_notification(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 6,
            'min_stock' => 5,
            'selling_price' => 100000,
        ]);

        // Sale of 2 units reduces stock from 6 to 4 (critical)
        $response = $this->actingAs($this->admin)->post(route('sales.store'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 100000,
                ],
            ],
            'notes' => 'Test low stock sale',
        ]);

        $response->assertRedirect();

        $product->refresh();
        $this->assertEquals(4, $product->stock);
        $this->assertEquals(1, $this->admin->unreadNotifications()->count());
    }

    public function test_stock_movement_out_triggers_low_stock_notification(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 8,
            'min_stock' => 5,
        ]);

        // Stock movement out of 4 units reduces stock from 8 to 4
        $response = $this->actingAs($this->staff)->post(route('stock-movements.store'), [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 4,
            'notes' => 'Pengurangan stok gudang',
        ]);

        $response->assertRedirect(route('stock-movements.index'));

        $product->refresh();
        $this->assertEquals(4, $product->stock);
        $this->assertEquals(1, $this->staff->unreadNotifications()->count());
    }

    public function test_mark_single_notification_as_read(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 2,
            'min_stock' => 5,
        ]);

        $product->notifyIfLowStock();

        $notification = $this->admin->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $response = $this->actingAs($this->admin)->post(route('notifications.mark-read', $notification->id));
        $response->assertRedirect(route('products.low-stock'));

        $this->assertEquals(0, $this->admin->fresh()->unreadNotifications()->count());
        $this->assertEquals(1, $this->admin->fresh()->readNotifications()->count());
    }

    public function test_mark_all_notifications_as_read(): void
    {
        $product1 = Product::factory()->create(['category_id' => $this->category->id, 'stock' => 1, 'min_stock' => 5]);
        $product2 = Product::factory()->create(['category_id' => $this->category->id, 'stock' => 2, 'min_stock' => 5]);

        $product1->notifyIfLowStock();
        $product2->notifyIfLowStock();

        $this->assertEquals(2, $this->admin->unreadNotifications()->count());

        $response = $this->actingAs($this->admin)->post(route('notifications.read-all'));
        $response->assertSessionHas('success');

        $this->assertEquals(0, $this->admin->fresh()->unreadNotifications()->count());
    }

    public function test_artisan_check_low_stock_command(): void
    {
        Product::factory()->create(['category_id' => $this->category->id, 'stock' => 3, 'min_stock' => 5]);
        Product::factory()->create(['category_id' => $this->category->id, 'stock' => 2, 'min_stock' => 5]);
        Product::factory()->create(['category_id' => $this->category->id, 'stock' => 20, 'min_stock' => 5]);

        $this->artisan('inventory:check-low-stock')
            ->expectsOutputToContain('Pengecekan stok kritis selesai. 2 notifikasi stok kritis baru diterbitkan.')
            ->assertExitCode(0);

        $this->assertEquals(2, $this->admin->unreadNotifications()->count());
    }
}
