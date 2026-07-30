<?php

namespace Tests\Feature;

use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDocumentDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_repository_document_and_related_files(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Hapus',
            'email' => 'admin.hapus@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        Storage::disk('local')->put('documents/laporan.pdf', 'pdf content');
        Storage::disk('public')->put('projects/project.zip', 'zip content');

        $document = RepositoryDocument::create([
            'kategori' => 'magang',
            'jenis_input' => 'upload',
            'nim' => '123456789',
            'nama' => 'Mahasiswa Hapus',
            'tahun' => '2026',
            'judul' => 'Dokumen yang Dihapus',
            'file_dokumen' => 'documents/laporan.pdf',
            'file_project' => 'projects/project.zip',
            'status' => 'pending',
            'tanggal_upload' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.data.mahasiswa'))
            ->delete(route('admin.documents.destroy', $document));

        $response
            ->assertRedirect(route('admin.data.mahasiswa'))
            ->assertSessionHas('status', 'Data dokumen "Dokumen yang Dihapus" berhasil dihapus.');

        $this->assertDatabaseMissing('repository_documents', [
            'id' => $document->id,
        ]);

        Storage::disk('local')->assertMissing('documents/laporan.pdf');
        Storage::disk('public')->assertMissing('projects/project.zip');
    }

    public function test_non_admin_cannot_delete_repository_document(): void
    {
        $mahasiswa = User::create([
            'name' => 'Mahasiswa Biasa',
            'email' => 'mahasiswa.biasa@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'status_akun' => 'aktif',
            'nim' => '987654321',
        ]);

        $document = RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nim' => '987654321',
            'nama' => 'Mahasiswa Biasa',
            'tahun' => '2026',
            'judul' => 'Dokumen Tetap Ada',
            'status' => 'pending',
            'tanggal_upload' => now(),
        ]);

        $response = $this
            ->actingAs($mahasiswa)
            ->delete(route('admin.documents.destroy', $document));

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('repository_documents', [
            'id' => $document->id,
        ]);
    }
}
