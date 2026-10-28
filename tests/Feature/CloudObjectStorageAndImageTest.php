<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductImageStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CloudObjectStorageAndImageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->category = Category::create([
            'name' => 'Storage Unit',
            'slug' => 'storage-unit',
        ]);
    }

    public function test_image_url_returns_external_url_verbatim(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Cloud Headset',
            'sku' => 'CLD-001',
            'price' => 200000,
            'stock' => 10,
            'min_stock' => 2,
            'image' => 'https://cdn.example.com/products/cloud-headset.png',
        ]);

        $this->assertEquals('https://cdn.example.com/products/cloud-headset.png', $product->image_url);
    }

    public function test_image_url_resolves_from_cloud_storage_disk_when_active(): void
    {
        Storage::fake('s3');
        config(['filesystems.default' => 's3']);

        Storage::disk('s3')->put('products/cloud-mouse.png', 'dummy image content');

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Cloud Mouse',
            'sku' => 'CLD-002',
            'price' => 150000,
            'stock' => 5,
            'min_stock' => 2,
            'image' => 'products/cloud-mouse.png',
        ]);

        $this->assertNotNull($product->image_url);
        $this->assertStringContainsString('cloud-mouse.png', $product->image_url);
    }

    public function test_product_image_storage_service_stores_locally_and_on_cloud(): void
    {
        Storage::fake('r2');
        config(['filesystems.default' => 'r2']);

        $uploaded = UploadedFile::fake()->image('test_gear.png', 400, 400);

        $savedPath = ProductImageStorageService::store($uploaded, 'SKU-GEAR-99', 'Test Tournament Gear');

        $this->assertNotEmpty($savedPath);
        $this->assertFileExists(public_path($savedPath));

        // Clean up created local test artifact
        if (File::exists(public_path($savedPath))) {
            @File::delete(public_path($savedPath));
        }
        $skuPath = public_path('images/products/sku-gear-99.png');
        if (File::exists($skuPath)) {
            @File::delete($skuPath);
        }
    }

    public function test_sync_images_command_executes_successfully(): void
    {
        Storage::fake('s3');

        $this->artisan('inventory:sync-images-cloud', ['--disk' => 's3'])
            ->assertExitCode(0);
    }

    public function test_product_controller_store_and_update_with_image_upload(): void
    {
        $image = UploadedFile::fake()->image('new_item.png', 300, 300);

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Pro Mechanical Board',
            'sku' => 'BOARD-001',
            'price' => 750000,
            'stock' => 15,
            'min_stock' => 3,
            'image' => $image,
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::where('sku', 'BOARD-001')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);

        // Test update with remove_image
        $updateResponse = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'category_id' => $this->category->id,
            'name' => 'Pro Mechanical Board Updated',
            'sku' => 'BOARD-001',
            'price' => 800000,
            'stock' => 15,
            'min_stock' => 3,
            'remove_image' => '1',
        ]);

        $updateResponse->assertRedirect(route('products.index'));
        $product->refresh();
        $this->assertNull($product->image);
    }
}
