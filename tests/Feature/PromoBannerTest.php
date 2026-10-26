<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PromoBannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_banner_management_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Boss',
            'email' => 'admin@example.com',
        ]);

        PromoBanner::create([
            'tag' => 'PROMO GAJIAN:',
            'title' => 'Diskon 50% All Items',
            'description' => 'Khusus pengguna baru dan lama.',
            'button_text' => 'Ambil Diskon',
            'button_url' => '#promo',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/banners');
        $response->assertStatus(200);
        $response->assertSee('Banner Promo Beranda');
        $response->assertSee('Diskon 50% All Items');
        $response->assertSee('Ambil Diskon');
    }

    public function test_staff_and_buyer_cannot_access_banner_management(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $buyer = User::factory()->create(['role' => 'pembeli']);

        $this->actingAs($staff)->get('/banners')->assertStatus(403);
        $this->actingAs($buyer)->get('/banners')->assertStatus(403);
    }

    public function test_admin_can_update_banner(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $banner = PromoBanner::create([
            'tag' => 'TAG AWAL:',
            'title' => 'Judul Lama',
            'description' => 'Deskripsi lama',
            'button_text' => 'Beli',
            'button_url' => '#link',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put("/banners/{$banner->id}", [
            'tag' => 'PROMO SUPER DEAL:',
            'title' => 'Diskon 25% Laptop Gaming',
            'description' => 'Spesial akhir pekan hanya di Ctrl+R.',
            'button_text' => 'Beli Sekarang',
            'button_url' => '#katalog-grid',
            'is_active' => 1,
        ]);

        $response->assertRedirect('/banners');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('promo_banners', [
            'id' => $banner->id,
            'tag' => 'PROMO SUPER DEAL:',
            'title' => 'Diskon 25% Laptop Gaming',
            'button_text' => 'Beli Sekarang',
            'is_active' => true,
        ]);
    }

    public function test_buyer_dashboard_displays_updated_banner_and_manual_price_inputs(): void
    {
        $buyer = User::factory()->create(['role' => 'pembeli']);

        PromoBanner::create([
            'tag' => 'FLASH SALE 10.10:',
            'title' => 'Potongan Kilat Komponen PC',
            'description' => 'Garansi resmi distributor 3 tahun.',
            'button_text' => 'Lihat Diskon',
            'button_url' => '#katalog-grid',
            'is_active' => true,
        ]);

        $category = Category::factory()->create(['name' => 'Gaming Gear']);
        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Mouse Gaming ROG',
            'selling_price' => 750000,
            'price' => 750000,
            'stock' => 10,
        ]);
        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'PC Gaming Monster',
            'selling_price' => 25000000,
            'price' => 25000000,
            'stock' => 5,
        ]);

        $response = $this->actingAs($buyer)->get('/dashboard');
        $response->assertStatus(200);

        // Verify active banner rendered
        $response->assertSee('FLASH SALE 10.10:');
        $response->assertSee('Potongan Kilat Komponen PC');
        $response->assertSee('Garansi resmi distributor 3 tahun.');
        $response->assertSee('Lihat Diskon');

        // Verify manual price containers exist
        $response->assertSee('name="min_price"', false);
        $response->assertSee('name="max_price"', false);
        $response->assertSee('price-input-box');

        // Verify rating filter is removed
        $response->assertDontSee('<div class="filter-section-title">Rating</div>', false);
    }

    public function test_buyer_can_filter_with_manual_min_and_max_price(): void
    {
        $buyer = User::factory()->create(['role' => 'pembeli']);
        $category = Category::factory()->create();

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Mouse Murah',
            'selling_price' => 200000,
            'price' => 200000,
            'stock' => 5,
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Keyboard Mekanikal',
            'selling_price' => 1500000,
            'price' => 1500000,
            'stock' => 5,
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Laptop Sultan',
            'selling_price' => 28000000,
            'price' => 28000000,
            'stock' => 2,
        ]);

        // Filter: min 1.000.000, max 2.000.000
        $response = $this->actingAs($buyer)->get('/dashboard?min_price=1000000&max_price=2000000');
        $response->assertStatus(200);
        $response->assertSee('Keyboard Mekanikal');
        $response->assertDontSee('Mouse Murah');
        $response->assertDontSee('Laptop Sultan');
    }

    public function test_admin_can_upload_update_and_remove_banner_image(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $fakeImage = UploadedFile::fake()->image('promo-pc.png', 800, 400);

        // 1. Create with image
        $createResponse = $this->actingAs($admin)->post('/banners', [
            'tag' => 'DISKON TAHUN BARU:',
            'title' => 'Promo Spesial Rig PC',
            'description' => 'Diskon aksesoris gaming hingga 30%.',
            'button_text' => 'Belanja Sekarang',
            'button_url' => '#katalog-grid',
            'is_active' => 1,
            'image' => $fakeImage,
        ]);

        $createResponse->assertRedirect('/banners');
        $banner = PromoBanner::where('title', 'Promo Spesial Rig PC')->firstOrFail();

        $this->assertNotNull($banner->image);
        $this->assertFileExists(public_path($banner->image));
        $this->assertNotNull($banner->image_url);

        // 2. Update with new image
        $newFakeImage = UploadedFile::fake()->image('promo-pc-v2.png', 800, 400);
        $oldImagePath = public_path($banner->image);

        $updateResponse = $this->actingAs($admin)->put("/banners/{$banner->id}", [
            'tag' => 'DISKON TAHUN BARU V2:',
            'title' => 'Promo Spesial Rig PC V2',
            'button_text' => 'Belanja Sekarang',
            'button_url' => '#katalog-grid',
            'is_active' => 1,
            'image' => $newFakeImage,
        ]);

        $updateResponse->assertRedirect('/banners');
        $banner->refresh();

        $this->assertFileDoesNotExist($oldImagePath);
        $this->assertFileExists(public_path($banner->image));

        // 3. Update and remove image
        $currentImagePath = public_path($banner->image);
        $removeResponse = $this->actingAs($admin)->put("/banners/{$banner->id}", [
            'tag' => 'DISKON TAHUN BARU V2:',
            'title' => 'Promo Spesial Rig PC V2',
            'button_text' => 'Belanja Sekarang',
            'button_url' => '#katalog-grid',
            'is_active' => 1,
            'remove_image' => 1,
        ]);

        $removeResponse->assertRedirect('/banners');
        $banner->refresh();

        $this->assertNull($banner->image);
        $this->assertNull($banner->image_url);
        $this->assertFileDoesNotExist($currentImagePath);
    }
}
