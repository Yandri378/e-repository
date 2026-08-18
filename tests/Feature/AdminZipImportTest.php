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
            'status' => 'pending',
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
        $this->assertStringContainsString('Tidak ditemukan berkas dokumen', $response->json('message'));
        $this->assertStringContainsString('gambar.png', $response->json('message'));

        if (file_exists($tempZipPath)) {
            unlink($tempZipPath);
        }
    }

    public function test_admin_can_import_documents_from_nested_zip_archives(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        // Create an inner zip containing a PDF
        $innerZipPath = tempnam(sys_get_temp_dir(), 'inner_') . '.zip';
        $innerZip = new \ZipArchive();
        $innerZip->open($innerZipPath, \ZipArchive::CREATE);
        $innerZip->addFromString('Skripsi_Inner_Nested.pdf', '%PDF-1.4 dummy pdf');
        $innerZip->close();

        // Create outer zip containing inner zip
        $outerZipPath = tempnam(sys_get_temp_dir(), 'outer_') . '.zip';
        $outerZip = new \ZipArchive();
        $outerZip->open($outerZipPath, \ZipArchive::CREATE);
        $outerZip->addFile($innerZipPath, 'subfolder/Inner_Archive.zip');
        $outerZip->close();

        $uploadedZip = new UploadedFile(
            $outerZipPath,
            'nested_archive.zip',
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
            'nama' => 'Skripsi_Inner_Nested',
            'kategori' => 'skripsi',
        ]);

        @unlink($innerZipPath);
        @unlink($outerZipPath);
    }

    public function test_admin_can_import_rtf_and_other_documents_from_zip(): void
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
        $zip->addFromString('SKRIPSI JEKI ARYA DINATA 161100269.rtf', '{\rtf1\ansi Dummy RTF content}');
        $zip->close();

        $uploadedZip = new UploadedFile(
            $tempZipPath,
            'dokumen_rtf.zip',
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
}
