<?php

namespace Tests\Feature;

use App\Models\RepositorySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUploadSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_all_upload_sessions(): void
    {
        $admin = User::create([
            'name' => 'Admin Sesi',
            'email' => 'admin.sesi@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.dashboard'))
            ->patch(route('admin.settings.upload-session'), [
                'kategori' => 'all',
                'status' => 'open',
            ]);

        $response->assertRedirect(route('admin.dashboard'));

        foreach (['skripsi', 'magang', 'pkm', 'penelitian'] as $kategori) {
            $this->assertDatabaseHas('repository_settings', [
                'key' => 'upload_'.$kategori,
                'value' => 'open',
            ]);
        }

        $this->assertTrue(RepositorySetting::uploadOpen('skripsi'));
        $this->assertTrue(RepositorySetting::uploadOpen('magang'));
        $this->assertTrue(RepositorySetting::uploadOpen('pkm'));
        $this->assertTrue(RepositorySetting::uploadOpen('penelitian'));
    }

    public function test_admin_can_close_all_upload_sessions(): void
    {
        $admin = User::create([
            'name' => 'Admin Sesi',
            'email' => 'admin.close.sesi@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        foreach (['skripsi', 'magang', 'pkm', 'penelitian'] as $kategori) {
            RepositorySetting::create([
                'key' => 'upload_'.$kategori,
                'value' => 'open',
            ]);
        }

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.dashboard'))
            ->patch(route('admin.settings.upload-session'), [
                'kategori' => 'all',
                'status' => 'closed',
            ]);

        $response->assertRedirect(route('admin.dashboard'));

        foreach (['skripsi', 'magang', 'pkm', 'penelitian'] as $kategori) {
            $this->assertDatabaseHas('repository_settings', [
                'key' => 'upload_'.$kategori,
                'value' => 'closed',
            ]);
        }
    }
}
