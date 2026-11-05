<?php

namespace Tests\Feature;

use App\Models\JenisDokumen;
use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\RepositorySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadAllCategoriesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $dosen;
    protected User $mahasiswa;
    protected ProgramStudi $prodi;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->admin = User::create([
            'name'        => 'Admin Test',
            'email'       => 'admin@test.com',
            'password'    => bcrypt('password'),
            'role'        => 'admin',
            'status_akun' => 'aktif',
        ]);

        $this->prodi = ProgramStudi::create([
            'nama'  => 'Teknik Informatika',
            'kode'  => 'TI',
            'aktif' => true,
        ]);

        $this->dosen = User::create([
            'name'             => 'Dosen Test',
            'email'            => 'dosen@test.com',
            'password'         => bcrypt('password'),
            'role'             => 'dosen',
            'status_akun'      => 'aktif',
            'nidn'             => '1234567890',
            'program_studi_id' => $this->prodi->id,
        ]);

        $this->mahasiswa = User::create([
            'name'             => 'Mahasiswa Test',
            'email'            => 'mahasiswa@test.com',
            'password'         => bcrypt('password'),
            'role'             => 'mahasiswa',
            'status_akun'      => 'aktif',
            'nim'              => '2026001001',
            'program_studi_id' => $this->prodi->id,
        ]);

        JenisDokumen::create(['nama' => 'Laporan Magang',   'kategori' => 'magang',    'aktif' => true]);
        JenisDokumen::create(['nama' => 'Penelitian',       'kategori' => 'penelitian','aktif' => true]);
        JenisDokumen::create(['nama' => 'PKM',              'kategori' => 'pkm',       'aktif' => true]);

        RepositorySetting::create(['key' => 'upload_skripsi',   'value' => 'open']);
        RepositorySetting::create(['key' => 'upload_magang',    'value' => 'open']);
        RepositorySetting::create(['key' => 'upload_pkm',       'value' => 'open']);
        RepositorySetting::create(['key' => 'upload_penelitian','value' => 'open']);
    }

    // ─── ADMIN MANUAL UPLOAD ───────────────────────────────────────────────

    public function test_admin_can_manual_upload_magang(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori'        => 'magang',
            'nama'            => 'Mahasiswa Magang',
            'nim'             => '2026001002',
            'program_studi_id'=> $this->prodi->id,
            'tahun'           => '2026',
            'judul'           => 'Laporan Magang PT ABC',
            'tempat_magang'   => 'PT ABC Indonesia',
            'file_dokumen'    => UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));
        $this->assertDatabaseHas('repository_documents', [
            'kategori'     => 'magang',
            'nama'         => 'Mahasiswa Magang',
            'nim'          => '2026001002',
            'tempat_magang'=> 'PT ABC Indonesia',
            'status'       => 'terverifikasi',
        ]);
    }

    public function test_admin_can_manual_upload_penelitian(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori'          => 'penelitian',
            'nama'              => 'Dosen Peneliti',
            'nidn'              => '9876543210',
            'program_studi_id'  => $this->prodi->id,
            'tahun'             => '2026',
            'judul'             => 'Penelitian Dosen AI',
            'status_penelitian' => 'selesai',
            'file_dokumen'      => UploadedFile::fake()->create('penelitian.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));
        $this->assertDatabaseHas('repository_documents', [
            'kategori' => 'penelitian',
            'nidn'     => '9876543210',
            'status'   => 'terverifikasi',
        ]);
    }

    public function test_admin_can_manual_upload_pkm(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'kategori'        => 'pkm',
            'nama'            => 'Dosen PKM',
            'nidn'            => '1111222233',
            'program_studi_id'=> $this->prodi->id,
            'tahun'           => '2026',
            'judul'           => 'PKM Dosen 2026',
            'file_dokumen'    => UploadedFile::fake()->create('pkm.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.documents.index', ['status' => 'terverifikasi']));
        $this->assertDatabaseHas('repository_documents', [
            'kategori' => 'pkm',
            'nidn'     => '1111222233',
            'status'   => 'terverifikasi',
        ]);
    }

    // ─── DOSEN UPLOAD ──────────────────────────────────────────────────────

    public function test_dosen_can_upload_penelitian(): void
    {
        $response = $this->actingAs($this->dosen)->post(route('repository.store', 'penelitian'), [
            'nama'            => $this->dosen->name,
            'nidn'            => $this->dosen->nidn,
            'program_studi_id'=> $this->prodi->id,
            'tahun'           => '2026',
            'judul'           => 'Judul Penelitian Dosen',
            'file_dokumen'    => UploadedFile::fake()->create('penelitian.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('repository_documents', [
            'kategori' => 'penelitian',
            'user_id'  => $this->dosen->id,
            'status'   => 'pending',
        ]);
    }

    public function test_dosen_can_upload_pkm(): void
    {
        $response = $this->actingAs($this->dosen)->post(route('repository.store', 'pkm'), [
            'nama'            => $this->dosen->name,
            'nidn'            => $this->dosen->nidn,
            'program_studi_id'=> $this->prodi->id,
            'tahun'           => '2026',
            'judul'           => 'Judul PKM Dosen',
            'file_dokumen'    => UploadedFile::fake()->create('pkm.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('repository_documents', [
            'kategori' => 'pkm',
            'user_id'  => $this->dosen->id,
            'status'   => 'pending',
        ]);
    }

    // ─── MAHASISWA UPLOAD ──────────────────────────────────────────────────

    public function test_mahasiswa_can_upload_magang(): void
    {
        $response = $this->actingAs($this->mahasiswa)->post(route('repository.store', 'magang'), [
            'nama'                      => $this->mahasiswa->name,
            'nim'                       => $this->mahasiswa->nim,
            'program_studi_id'          => $this->prodi->id,
            'dosen_pembimbing_id'       => $this->dosen->id,
            'tahun'                     => '2026',
            'judul'                     => 'Laporan Magang Mahasiswa',
            'tempat_magang'             => 'PT Testing Indonesia',
            'file_dokumen'              => UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf'),
            'pdf_kelengkapan_deklarasi' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('repository_documents', [
            'kategori'     => 'magang',
            'user_id'      => $this->mahasiswa->id,
            'tempat_magang'=> 'PT Testing Indonesia',
            'status'       => 'pending',
        ]);
    }

    public function test_mahasiswa_upload_magang_requires_pdf_declaration(): void
    {
        $response = $this->actingAs($this->mahasiswa)
            ->from(route('repository.create', 'magang'))
            ->post(route('repository.store', 'magang'), [
                'nama'                => $this->mahasiswa->name,
                'nim'                 => $this->mahasiswa->nim,
                'program_studi_id'    => $this->prodi->id,
                'dosen_pembimbing_id' => $this->dosen->id,
                'tahun'               => '2026',
                'judul'               => 'Laporan Magang',
                'file_dokumen'        => UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf'),
            ]);

        $response->assertSessionHasErrors('pdf_kelengkapan_deklarasi');
        $this->assertDatabaseCount('repository_documents', 0);
    }

    public function test_dosen_cannot_upload_magang(): void
    {
        $response = $this->actingAs($this->dosen)->post(route('repository.store', 'magang'), [
            'nama'         => $this->dosen->name,
            'nidn'         => $this->dosen->nidn,
            'program_studi_id' => $this->prodi->id,
            'tahun'        => '2026',
            'judul'        => 'Laporan Magang Dosen (Tidak Boleh)',
            'file_dokumen' => UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf'),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseCount('repository_documents', 0);
    }
}
