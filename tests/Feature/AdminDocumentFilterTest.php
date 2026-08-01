<?php

namespace Tests\Feature;

use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDocumentFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_index_shows_only_pending_documents(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin.filter@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $pendingDoc = RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nim' => '11111',
            'nama' => 'Mahasiswa Pending',
            'tahun' => '2026',
            'judul' => 'Judul Status Pending',
            'status' => 'pending',
            'tanggal_upload' => now(),
        ]);

        $verifiedDoc = RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nim' => '22222',
            'nama' => 'Mahasiswa Terverifikasi',
            'tahun' => '2026',
            'judul' => 'Judul Status Terverifikasi',
            'status' => 'terverifikasi',
            'tanggal_upload' => now(),
        ]);

        // Access index without query string -> default filter 'pending'
        $response = $this->actingAs($admin)->get(route('admin.documents.index'));

        $response->assertOk();
        $response->assertSee('Judul Status Pending');
        $response->assertDontSee('Judul Status Terverifikasi');
    }

    public function test_index_with_status_terverifikasi_shows_verified_documents(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin.filter2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $pendingDoc = RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nim' => '11111',
            'nama' => 'Mahasiswa Pending',
            'tahun' => '2026',
            'judul' => 'Judul Status Pending',
            'status' => 'pending',
            'tanggal_upload' => now(),
        ]);

        $verifiedDoc = RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nim' => '22222',
            'nama' => 'Mahasiswa Terverifikasi',
            'tahun' => '2026',
            'judul' => 'Judul Status Terverifikasi',
            'status' => 'terverifikasi',
            'tanggal_upload' => now(),
        ]);

        // Access index with ?status=terverifikasi
        $response = $this->actingAs($admin)->get(route('admin.documents.index', ['status' => 'terverifikasi']));

        $response->assertOk();
        $response->assertSee('Judul Status Terverifikasi');
        $response->assertDontSee('Judul Status Pending');
    }
}
