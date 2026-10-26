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
        $response->assertSee('admin-nav-profile-btn');
        $response->assertSee('Pengaturan Akun');
        $response->assertSee('Admin Executive');
        $response->assertDontSee('Storefront');
    }

    public function test_user_can_access_all_four_settings_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Fikri Admin']);

        $this->actingAs($admin)->get('/settings/profile')
            ->assertStatus(200)
            ->assertSee('Profil Saya');

        $this->actingAs($admin)->get('/settings/contact')
            ->assertStatus(200)
            ->assertSee('Kontak & Lokasi')
            ->assertSee($admin->email);

        $this->actingAs($admin)->get('/settings/security')
            ->assertStatus(200)
            ->assertSee('Keamanan Akun')
            ->assertSee('Perbarui Kata Sandi');

        $this->actingAs($admin)->get('/settings/preferences')
            ->assertStatus(200)
            ->assertSee('Preferensi Sistem')
            ->assertSee('Dark Glassmorphic UI');
    }

    public function test_user_can_update_contact_and_security_separately(): void
    {
        $user = User::factory()->create([
            'email' => 'old_email@example.com',
            'password' => Hash::make('secret123'),
        ]);

        // Update Contact
        $this->actingAs($user)->put('/settings/contact', [
            'email' => 'new_email@example.com',
            'phone_number' => '089912345678',
            'address' => 'Gudang Utama Blok B',
        ])->assertRedirect('/settings/contact')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'new_email@example.com']);
        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id, 'phone_number' => '089912345678']);

        // Update Security
        $this->actingAs($user)->put('/settings/security', [
            'current_password' => 'secret123',
            'new_password' => 'newpass456',
            'new_password_confirmation' => 'newpass456',
        ])->assertRedirect('/settings/security')
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpass456', $user->password));

        // Update Preferences
        $this->actingAs($user)->put('/settings/preferences', [])
            ->assertRedirect('/settings/preferences')
            ->assertSessionHas('success');
    }
}
