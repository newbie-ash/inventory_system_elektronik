<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_promo(): void
    {
        $response = $this->get('/promo');
        $response->assertRedirect('/login');
    }

    public function test_buyer_can_view_promo_page_with_vouchers(): void
    {
        $buyer = User::factory()->create(['role' => 'pembeli']);

        $response = $this->actingAs($buyer)->get('/promo');
        $response->assertStatus(200);
        $response->assertSee('Kupon & Voucher Diskon', false);
        $response->assertSee('GAJIAN15');
        $response->assertSee('GEAR50K');
        $response->assertSee('ONGKIRFREE');
        $response->assertSee('SULTAN1JT');
        $response->assertSee('Salin');
    }

    public function test_promo_page_displays_active_banner(): void
    {
        $buyer = User::factory()->create(['role' => 'pembeli']);

        PromoBanner::create([
            'tag' => 'PROMO PEKAN INI:',
            'title' => 'Flash Sale Monitor Gaming 240Hz',
            'description' => 'Dapatkan potongan harga khusus untuk pembelian monitor.',
            'button_text' => 'Klaim Sekarang',
            'button_url' => '#daftar-voucher',
            'is_active' => true,
        ]);

        $response = $this->actingAs($buyer)->get('/promo');
        $response->assertStatus(200);
        $response->assertSee('PROMO PEKAN INI:');
        $response->assertSee('Flash Sale Monitor Gaming 240Hz');
        $response->assertSee('Dapatkan potongan harga khusus untuk pembelian monitor.');
    }

    public function test_buyer_can_filter_promo_products_by_category(): void
    {
        $buyer = User::factory()->create(['role' => 'pembeli']);

        $catLaptop = Category::factory()->create(['name' => 'Laptop']);
        $catMouse = Category::factory()->create(['name' => 'Mouse Gaming']);

        Product::factory()->create([
            'category_id' => $catLaptop->id,
            'name' => 'ASUS ROG Strix G16',
            'selling_price' => 22000000,
            'price' => 22000000,
            'stock' => 5,
        ]);

        Product::factory()->create([
            'category_id' => $catMouse->id,
            'name' => 'Logitech G Pro X Superlight',
            'selling_price' => 1800000,
            'price' => 1800000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($buyer)->get('/promo?category_id='.$catLaptop->id);
        $response->assertStatus(200);
        $response->assertSee('ASUS ROG Strix G16');
        $response->assertDontSee('Logitech G Pro X Superlight');
    }
}
