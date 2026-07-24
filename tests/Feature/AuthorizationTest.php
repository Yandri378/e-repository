<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $mahasiswa;
    protected User $dosen;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $this->mahasiswa = User::create([
            'name' => 'Mahasiswa User',
            'email' => 'mahasiswa@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'status_akun' => 'aktif',
            'nim' => '123456789',
        ]);

        $this->dosen = User::create([
            'name' => 'Dosen User',
            'email' => 'dosen@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'status_akun' => 'aktif',
            'nidn' => '0123456789',
        ]);
    }

    /**
     * Test Admin can access admin dashboard
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboards.admin');
    }

    /**
     * Test Mahasiswa CANNOT access admin dashboard
     */
    public function test_mahasiswa_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->mahasiswa)->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test Dosen CANNOT access admin dashboard
     */
    public function test_dosen_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->dosen)->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test Admin CANNOT access mahasiswa dashboard
     */
    public function test_admin_cannot_access_mahasiswa_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/mahasiswa/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test Mahasiswa can access mahasiswa dashboard
     */
    public function test_mahasiswa_can_access_mahasiswa_dashboard(): void
    {
        $response = $this->actingAs($this->mahasiswa)->get('/mahasiswa/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboards.mahasiswa');
    }

    /**
     * Test Dosen can access dosen dashboard
     */
    public function test_dosen_can_access_dosen_dashboard(): void
    {
        $response = $this->actingAs($this->dosen)->get('/dosen/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboards.dosen');
    }

    /**
     * Test Unauthenticated user cannot access any dashboard
     */
    public function test_unauthenticated_cannot_access_any_dashboard(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/mahasiswa/dashboard')->assertRedirect('/login');
        $this->get('/dosen/dashboard')->assertRedirect('/login');
    }

    /**
     * Test Inactive mahasiswa cannot access dashboard
     */
    public function test_inactive_mahasiswa_redirected_to_login(): void
    {
        $this->mahasiswa->update(['status_akun' => 'menunggu_verifikasi']);

        $response = $this->actingAs($this->mahasiswa)->get('/mahasiswa/dashboard');
        // Should be redirected during request due to account.active middleware
        $response->assertRedirect('/login');
    }

    /**
     * Test inactive admin cannot access admin dashboard
     */
    public function test_inactive_admin_redirected_to_admin_login(): void
    {
        $this->admin->update(['status_akun' => 'menunggu_verifikasi']);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test Admin data endpoints require role:admin
     */
    public function test_mahasiswa_cannot_access_admin_data_endpoints(): void
    {
        $endpoints = [
            '/admin/data-mahasiswa',
            '/admin/users/verifikasi',
            '/admin/uploads/verifikasi',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->actingAs($this->mahasiswa)->get($endpoint);
            $response->assertRedirect('/login');
        }
    }

    /**
     * Test admin can access account verification page
     */
    public function test_admin_can_access_account_verification_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/users/verifikasi');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.pending');
    }

    /**
     * Test admin can update account status
     */
    public function test_admin_can_update_account_status(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->from('/admin/users')
            ->patch('/admin/users/' . $this->mahasiswa->id . '/status', [
                'status_akun' => 'nonaktif',
            ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $this->mahasiswa->id,
            'status_akun' => 'nonaktif',
        ]);
    }

    /**
     * Test admin can delete user
     */
    public function test_admin_can_delete_user(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->from('/admin/users')
            ->delete('/admin/users/' . $this->mahasiswa->id);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', [
            'id' => $this->mahasiswa->id,
        ]);
    }

    /**
     * Test Report page requires admin role
     */

    /**
     * Test Report page requires admin role
     */
    public function test_mahasiswa_cannot_access_reports(): void
    {
        $response = $this->actingAs($this->mahasiswa)->get('/laporan');
        $response->assertStatus(403);
    }

    /**
     * Test Admin can access reports
     */
    public function test_admin_can_access_reports(): void
    {
        $response = $this->actingAs($this->admin)->get('/laporan');
        $response->assertStatus(200);
    }

    /**
     * Test single login redirects Mahasiswa to Mahasiswa dashboard.
     */
    public function test_single_login_redirects_mahasiswa_to_mahasiswa_dashboard(): void
    {
        $response = $this->post('/login', [
            'identifier' => '123456789',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticatedAs($this->mahasiswa);
    }

    /**
     * Test single login redirects Dosen to Dosen dashboard.
     */
    public function test_single_login_redirects_dosen_to_dosen_dashboard(): void
    {
        $response = $this->post('/login', [
            'identifier' => '0123456789',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dosen.dashboard'));
        $this->assertAuthenticatedAs($this->dosen);
    }
}
