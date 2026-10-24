<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_settings_page(): void
    {
        $response = $this->get('/settings/profile');
        $response->assertRedirect('/login');
    }

    public function test_buyer_can_view_settings_page(): void
    {
        $buyer = User::factory()->create([
            'role' => 'pembeli',
            'name' => 'Lek Ku',
            'email' => 'lekku@example.com',
        ]);

        $response = $this->actingAs($buyer)->get('/settings/profile');
        $response->assertStatus(200);
        $response->assertSee('Edit Profil Profil Utama');
        $response->assertSee('lekku@example.com');
    }

    public function test_buyer_can_update_profile_and_contact(): void
    {
        $buyer = User::factory()->create([
            'role' => 'pembeli',
            'name' => 'Lek Ku',
            'email' => 'lekku@example.com',
        ]);

        $response = $this->actingAs($buyer)->put('/settings/profile', [
            'name' => 'Lek Ku Updated',
            'username' => 'lekku99',
            'email' => 'lekku_new@example.com',
            'phone_number' => '081234567890',
            'address' => 'Jl. Merdeka No. 45, Jakarta',
        ]);

        $response->assertRedirect('/settings/profile');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $buyer->id,
            'name' => 'Lek Ku Updated',
            'email' => 'lekku_new@example.com',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $buyer->id,
            'full_name' => 'Lek Ku Updated',
            'department' => 'lekku99',
            'phone_number' => '081234567890',
            'bio' => 'Jl. Merdeka No. 45, Jakarta',
        ]);
    }

    public function test_buyer_can_change_password(): void
    {
        $buyer = User::factory()->create([
            'role' => 'pembeli',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($buyer)->put('/settings/profile', [
            'name' => $buyer->name,
            'email' => $buyer->email,
            'current_password' => 'password123',
            'new_password' => 'newpassword789',
        ]);

        $response->assertRedirect('/settings/profile');
        $buyer->refresh();
        $this->assertTrue(Hash::check('newpassword789', $buyer->password));
    }

    public function test_admin_can_access_settings_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Boss',
            'email' => 'admin@example.com',
        ]);

        $response = $this->actingAs($admin)->get('/settings/profile');
        $response->assertStatus(200);
        $response->assertSee('Admin Boss');
        $response->assertSee('sidebarToggle');
    }

    public function test_staff_can_access_settings_page(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'name' => 'Staff Gudang',
            'email' => 'staff@example.com',
        ]);

        $response = $this->actingAs($staff)->get('/settings/profile');
        $response->assertStatus(200);
        $response->assertSee('Staff Gudang');
        $response->assertSee('sidebarToggle');
    }

    public function test_admin_dashboard_renders_collapsible_sidebar_and_relocated_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Executive',
            'email' => 'exec@example.com',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('id="sidebarToggle"', false);
        $response->assertSee('sidebar-user-footer');
        $response->assertSee('Pengaturan Akun');
        $response->assertSee('Admin Executive');
    }
}
