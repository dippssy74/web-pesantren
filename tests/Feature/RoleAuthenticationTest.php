<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_dashboards(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/wali/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_admin_can_access_admin_dashboard_view(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrasi');
        $response->assertSee('Pendaftaran (PSB)');
        $response->assertSee('Keuangan');
        $response->assertSee('Koperasi');
        $response->assertSee('Donasi');
    }

    public function test_admin_cannot_access_wali_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/wali/dashboard');

        $response->assertStatus(403);
    }

    public function test_wali_santri_can_login_and_is_redirected_to_wali_dashboard(): void
    {
        $wali = User::factory()->create([
            'role' => 'wali_santri',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $wali->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($wali);
        $response->assertRedirect('/wali/dashboard');
    }

    public function test_wali_santri_can_access_wali_dashboard_view(): void
    {
        $wali = User::factory()->create(['role' => 'wali_santri']);

        $response = $this->actingAs($wali)->get('/wali/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Portal Wali');
        $response->assertSee('Santri Cashless Card');
        $response->assertSee('Beranda');
        $response->assertSee('E-Wallet');
        $response->assertSee('SPP');
        $response->assertSee('Profil');
    }

    public function test_wali_santri_cannot_access_admin_dashboard(): void
    {
        $wali = User::factory()->create(['role' => 'wali_santri']);

        $response = $this->actingAs($wali)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_user_umum_is_redirected_to_home_on_login(): void
    {
        $umum = User::factory()->create([
            'role' => 'umum',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $umum->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($umum);
        $response->assertRedirect('/');
    }

    public function test_user_umum_cannot_access_admin_or_wali_dashboard(): void
    {
        $umum = User::factory()->create(['role' => 'umum']);

        $response = $this->actingAs($umum)->get('/admin/dashboard');
        $response->assertStatus(403);

        $response = $this->actingAs($umum)->get('/wali/dashboard');
        $response->assertStatus(403);
    }
}
