<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@test.com',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'status_akun' => 'aktif',
            'nim' => '200101001',
        ]);
    }

    public function test_user_can_access_profile_page(): void
    {
        $response = $this->actingAs($this->user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.index');
        $response->assertSee('Mahasiswa Test');
    }

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_user_can_update_profile_info(): void
    {
        $response = $this->actingAs($this->user)->patch('/profile', [
            'name' => 'Mahasiswa Update',
            'email' => 'mahasiswa_new@test.com',
            'whatsapp' => '081234567890',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Mahasiswa Update',
            'email' => 'mahasiswa_new@test.com',
            'whatsapp' => '081234567890',
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $response = $this->actingAs($this->user)->patch('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Password berhasil diperbarui.');

        $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
    }
}
