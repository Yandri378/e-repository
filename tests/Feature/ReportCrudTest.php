<?php

namespace Tests\Feature;

use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_reports_page_and_view_stats(): void
    {
        $admin = User::create([
            'name' => 'Admin Laporan',
            'email' => 'admin.laporan@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nim' => '12345',
            'nama' => 'Penulis Skripsi',
            'tahun' => '2026',
            'judul' => 'Uji Coba Laporan Skripsi',
            'status' => 'terverifikasi',
        ]);

        $response = $this->actingAs($admin)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('Laporan');
        $response->assertSee('Rekapitulasi');
        $response->assertSee('Uji Coba Laporan Skripsi');
        $response->assertSee('Penulis Skripsi');
    }

    public function test_admin_can_create_document_from_reports_page(): void
    {
        $admin = User::create([
            'name' => 'Admin Laporan',
            'email' => 'admin.laporan2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->post(route('reports.store'), [
            'judul' => 'Dokumen Baru dari Laporan',
            'nama' => 'Mahasiswa Laporan',
            'nim' => '99999',
            'kategori' => 'magang',
            'tahun' => 2026,
            'status' => 'pending',
            'abstrak' => 'Abstrak contoh',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('repository_documents', [
            'judul' => 'Dokumen Baru dari Laporan',
            'nama' => 'Mahasiswa Laporan',
            'nim' => '99999',
            'kategori' => 'magang',
        ]);
    }

    public function test_admin_can_update_document_from_reports_page(): void
    {
        $admin = User::create([
            'name' => 'Admin Laporan',
            'email' => 'admin.laporan3@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $document = RepositoryDocument::create([
            'kategori' => 'pkm',
            'jenis_input' => 'manual',
            'nidn' => '88888',
            'nama' => 'Dosen PKM',
            'tahun' => '2025',
            'judul' => 'Judul Lama',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('reports.update', $document), [
            'judul' => 'Judul Baru yang Diperbarui',
            'nama' => 'Dosen PKM Updated',
            'nidn' => '88888',
            'kategori' => 'pkm',
            'tahun' => 2025,
            'status' => 'terverifikasi',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('repository_documents', [
            'id' => $document->id,
            'judul' => 'Judul Baru yang Diperbarui',
            'status' => 'terverifikasi',
        ]);
    }

    public function test_admin_can_delete_document_from_reports_page(): void
    {
        $admin = User::create([
            'name' => 'Admin Laporan',
            'email' => 'admin.laporan4@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $document = RepositoryDocument::create([
            'kategori' => 'penelitian',
            'jenis_input' => 'manual',
            'nidn' => '77777',
            'nama' => 'Dosen Penelitian',
            'tahun' => '2026',
            'judul' => 'Dokumen Dihapus dari Laporan',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->delete(route('reports.destroy', $document));

        $response->assertRedirect();

        $this->assertDatabaseMissing('repository_documents', [
            'id' => $document->id,
        ]);
    }
}
