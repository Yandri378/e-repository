<?php

namespace Tests\Feature;

use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class AdminManualUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected ProgramStudi $prodi;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $this->prodi = ProgramStudi::create([
            'nama' => 'Teknik Informatika',
            'aktif' => true,
        ]);
    }

    public function test_admin_can_access_manual_upload_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.documents.create', ['kategori' => 'skripsi']));
        $response->assertStatus(200);
        $response->assertSee('Upload Manual Dokumen');
        $response->assertSee('Skripsi Mahasiswa');

        $responsePenelitian = $this->actingAs($this->admin)->get(route('admin.documents.create', ['kategori' => 'penelitian']));
        $responsePenelitian->assertStatus(200);
        $responsePenelitian->assertSee('Penelitian Dosen');

        $responsePkm = $this->actingAs($this->admin)->get(route('admin.documents.create', ['kategori' => 'pkm']));
        $responsePkm->assertStatus(200);
        $responsePkm->assertSee('PKM Dosen');
    }

    public function test_non_admin_cannot_access_manual_upload_page(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa', 'status_akun' => 'aktif']);

        $response = $this->actingAs($mahasiswa)->get(route('admin.documents.create'));
        $response->assertRedirect('/login');
    }

    public function test_get_manual_upload_store_url_redirects_to_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/uploads/store?kategori=pkm');

        $response->assertRedirect(route('admin.documents.create', ['kategori' => 'pkm']));
    }

    public function test_admin_can_manually_upload_skripsi_mahasiswa_with_pdf_and_project_zip(): void
    {
        $pdfFile = UploadedFile::fake()->create('skripsi.pdf', 200, 'application/pdf');
        $zipFile = UploadedFile::fake()->create('project_skripsi.zip', 500, 'application/zip');

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori' => 'skripsi',
            'nama' => 'Budi Mahasiswa',
            'nim' => '2010115261',
            'program_studi_id' => $this->prodi->id,
            'tahun' => '2026',
            'bulan' => '8',
            'judul' => 'Sistem Informasi Repository Kampus',
            'jumlah_halaman' => 110,
            'abstrak' => 'Abstrak skripsi mahasiswa budi.',
            'status_penelitian' => 'selesai',
            'file_dokumen' => $pdfFile,
            'file_project' => $zipFile,
        ]);

        $response->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));

        $document = RepositoryDocument::where('nim', '2010115261')->firstOrFail();
        $this->assertEquals('skripsi', $document->kategori);
        $this->assertEquals('Budi Mahasiswa', $document->nama);
        $this->assertEquals('terverifikasi', $document->status);
        $this->assertNotNull($document->file_dokumen);
        $this->assertNotNull($document->file_project);

        Storage::disk('local')->assertExists($document->file_dokumen);
        Storage::disk('local')->assertExists($document->file_project);
    }

    public function test_admin_manual_upload_falls_back_to_public_storage_when_private_disk_is_unavailable(): void
    {
        $pdfFile = UploadedFile::fake()->create('skripsi.pdf', 200, 'application/pdf');

        $localDisk = Mockery::mock();
        $localDisk->shouldReceive('putFileAs')->andThrow(new \RuntimeException('disk unavailable'));

        $publicDisk = Mockery::mock();
        $publicDisk->shouldReceive('putFileAs')->andReturn('repository-documents/skripsi.pdf');

        Storage::shouldReceive('disk')->with('local')->andReturn($localDisk);
        Storage::shouldReceive('disk')->with('public')->andReturn($publicDisk);

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori' => 'skripsi',
            'nama' => 'Fallback User',
            'nim' => '2024000001',
            'program_studi_id' => $this->prodi->id,
            'tahun' => '2026',
            'judul' => 'Fallback Upload',
            'file_dokumen' => $pdfFile,
        ]);

        $response->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));
        $this->assertDatabaseHas('repository_documents', ['judul' => 'Fallback Upload']);
    }

    public function test_admin_can_manually_upload_penelitian_dosen_with_pdf_and_rar_project(): void
    {
        $pdfFile = UploadedFile::fake()->create('penelitian.pdf', 300, 'application/pdf');
        $rarFile = UploadedFile::fake()->create('penelitian_dataset.rar', 800, 'application/x-rar-compressed');

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori' => 'penelitian',
            'nama' => 'Prof. Dr. Dosen Utama',
            'nidn' => '0011223344',
            'program_studi_id' => $this->prodi->id,
            'tahun' => '2026',
            'bulan' => '7',
            'judul' => 'Analisis Kecerdasan Buatan Terapan',
            'jumlah_halaman' => 45,
            'abstrak' => 'Abstrak penelitian dosen.',
            'detail' => 'Hibah penelitian Dikti 2026',
            'status_penelitian' => 'selesai',
            'file_dokumen' => $pdfFile,
            'file_project' => $rarFile,
        ]);

        $response->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));

        $document = RepositoryDocument::where('nidn', '0011223344')->where('kategori', 'penelitian')->firstOrFail();
        $this->assertEquals('penelitian', $document->kategori);
        $this->assertEquals('Prof. Dr. Dosen Utama', $document->nama);
        $this->assertEquals('terverifikasi', $document->status);
        $this->assertNotNull($document->file_dokumen);
        $this->assertNotNull($document->file_project);

        Storage::disk('local')->assertExists($document->file_dokumen);
        Storage::disk('local')->assertExists($document->file_project);
    }

    public function test_admin_can_manually_upload_pkm_dosen_with_pdf_and_project(): void
    {
        $pdfFile = UploadedFile::fake()->create('pkm.pdf', 250, 'application/pdf');
        $zipFile = UploadedFile::fake()->create('pkm_code.zip', 400, 'application/zip');

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori' => 'pkm',
            'nama' => 'Dr. Dosen Pengabdian',
            'nidn' => '9988776655',
            'program_studi_id' => $this->prodi->id,
            'tahun' => '2026',
            'judul' => 'Pelatihan Literasi Digital UMKM',
            'abstrak' => 'Abstrak kegiatan PKM dosen.',
            'status_penelitian' => 'selesai',
            'file_dokumen' => $pdfFile,
            'file_project' => $zipFile,
        ]);

        $response->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));

        $document = RepositoryDocument::where('nidn', '9988776655')->where('kategori', 'pkm')->firstOrFail();
        $this->assertEquals('pkm', $document->kategori);
        $this->assertEquals('Dr. Dosen Pengabdian', $document->nama);
        $this->assertEquals('terverifikasi', $document->status);
        $this->assertNotNull($document->file_dokumen);
        $this->assertNotNull($document->file_project);

        Storage::disk('local')->assertExists($document->file_dokumen);
        Storage::disk('local')->assertExists($document->file_project);
    }

    public function test_uploaded_document_appears_in_public_catalog(): void
    {
        $pdfFile = UploadedFile::fake()->create('skripsi_catalog.pdf', 200, 'application/pdf');

        $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori' => 'skripsi',
            'nama' => 'Andi Catalog Test',
            'nim' => '9999999999',
            'program_studi_id' => $this->prodi->id,
            'tahun' => '2025',
            'judul' => 'Judul Skripsi Andi Unik 99999',
            'file_dokumen' => $pdfFile,
        ])->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));

        // Verify document is in DB with correct status
        $document = RepositoryDocument::where('nim', '9999999999')->firstOrFail();
        $this->assertEquals('terverifikasi', $document->status);

        // Verify document appears on the public repository catalog page
        $catalogResponse = $this->get(route('repository.index'));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('Andi Catalog Test');
        $catalogResponse->assertSee('Judul Skripsi Andi Unik 99999');

        // Verify document appears when searching by title
        $searchResponse = $this->get(route('repository.index') . '?search=Judul+Skripsi+Andi+Unik');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Judul Skripsi Andi Unik 99999');
    }
}
