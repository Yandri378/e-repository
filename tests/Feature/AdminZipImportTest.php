<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminZipImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_pdf_files_from_zip(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        // Create a temporary zip with a dummy pdf inside
        $tempZipPath = tempnam(sys_get_temp_dir(), 'test_zip_') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($tempZipPath, \ZipArchive::CREATE);
        $zip->addFromString('Dokumen_Skripsi_Budi.pdf', '%PDF-1.4 dummy pdf content');
        $zip->close();

        $uploadedZip = new UploadedFile(
            $tempZipPath,
            'dokumen.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->actingAs($admin)
            ->post(route('admin.documents.import.zip'), [
                'kategori' => 'skripsi',
                'file_zip' => $uploadedZip,
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson([
                'success_count' => 1,
                'kategori' => 'skripsi',
            ]);

        $this->assertDatabaseHas('repository_documents', [
            'nama' => 'Dokumen_Skripsi_Budi',
            'kategori' => 'skripsi',
            'status' => 'terverifikasi',
        ]);

        $searchResponse = $this->get(route('repository.index', ['search' => 'Dokumen_Skripsi_Budi']));
        $searchResponse->assertOk()
            ->assertSee('Dokumen_Skripsi_Budi');

        if (file_exists($tempZipPath)) {
            unlink($tempZipPath);
        }
    }

    public function test_admin_can_import_zip_with_php_temp_file_path(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        // Create a temporary file without .zip extension, like PHP uploaded temp files (/tmp/php1234)
        $tempZipPath = tempnam(sys_get_temp_dir(), 'php_upload_test_');
        $zip = new \ZipArchive();
        $zip->open($tempZipPath, \ZipArchive::CREATE);
        $zip->addFromString('Dokumen_Skripsi_Siti.pdf', '%PDF-1.4 dummy pdf content');
        $zip->close();

        $uploadedZip = new UploadedFile(
            $tempZipPath,
            'skripsi_siti.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->actingAs($admin)
            ->post(route('admin.documents.import.zip'), [
                'kategori' => 'skripsi',
                'file_zip' => $uploadedZip,
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson([
                'success_count' => 1,
                'kategori' => 'skripsi',
            ]);

        $this->assertDatabaseHas('repository_documents', [
            'nama' => 'Dokumen_Skripsi_Siti',
            'kategori' => 'skripsi',
            'status' => 'terverifikasi',
        ]);

        if (file_exists($tempZipPath)) {
            unlink($tempZipPath);
        }
    }

    public function test_returns_diagnostic_error_when_no_documents_in_zip(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $tempZipPath = tempnam(sys_get_temp_dir(), 'test_zip_') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($tempZipPath, \ZipArchive::CREATE);
        $zip->addFromString('gambar.png', 'fake image bytes');
        $zip->close();

        $uploadedZip = new UploadedFile(
            $tempZipPath,
            'gambar.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->actingAs($admin)
            ->post(route('admin.documents.import.zip'), [
                'kategori' => 'skripsi',
                'file_zip' => $uploadedZip,
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(422);
        $this->assertStringContainsString('Tidak ditemukan berkas PDF', $response->json('message'));
        $this->assertStringContainsString('gambar.png', $response->json('message'));

        if (file_exists($tempZipPath)) {
            unlink($tempZipPath);
        }
    }

    public function test_admin_can_import_pdf_documents_from_zip(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $tempZipPath = tempnam(sys_get_temp_dir(), 'test_zip_') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($tempZipPath, \ZipArchive::CREATE);
        $zip->addFromString('SKRIPSI JEKI ARYA DINATA 161100269.pdf', '%PDF-1.4 Dummy PDF content');
        $zip->close();

        $uploadedZip = new UploadedFile(
            $tempZipPath,
            'dokumen_pdf.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->actingAs($admin)
            ->post(route('admin.documents.import.zip'), [
                'kategori' => 'skripsi',
                'file_zip' => $uploadedZip,
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson([
                'success_count' => 1,
                'kategori' => 'skripsi',
            ]);

        $this->assertDatabaseHas('repository_documents', [
            'nama' => 'SKRIPSI JEKI ARYA DINATA 161100269',
            'kategori' => 'skripsi',
        ]);

        if (file_exists($tempZipPath)) {
            unlink($tempZipPath);
        }
    }

    public function test_admin_can_download_non_pdf_document(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        Storage::disk('local')->put('repository-documents/test.txt', 'Sample text content');

        $document = \App\Models\RepositoryDocument::create([
            'user_id' => $admin->id,
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nama' => 'LICENSE',
            'tahun' => 2026,
            'judul' => 'LICENSE',
            'file_dokumen' => 'repository-documents/test.txt',
            'status' => 'pending',
            'tanggal_upload' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.documents.download', $document));

        $response->assertOk();
        $response->assertHeader('content-disposition');
    }
}
