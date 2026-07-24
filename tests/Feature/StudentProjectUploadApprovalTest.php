<?php

namespace Tests\Feature;

use App\Models\JenisDokumen;
use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentProjectUploadApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_can_upload_project_zip_and_dosen_can_approve(): void
    {
        Storage::fake('local');

        $programStudi = ProgramStudi::create([
            'nama' => 'Sistem Informasi',
            'kode' => 'SI',
            'aktif' => true,
        ]);

        $jenisDokumen = JenisDokumen::create([
            'nama' => 'Laporan Magang',
            'kategori' => 'magang',
            'aktif' => true,
        ]);

        $mahasiswa = User::create([
            'name' => 'Mahasiswa Project',
            'email' => 'mahasiswa.project@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'status_akun' => 'aktif',
            'nim' => '123456789',
            'program_studi_id' => $programStudi->id,
        ]);

        $dosen = User::create([
            'name' => 'Dosen Pembimbing',
            'email' => 'dosen.project@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'status_akun' => 'aktif',
            'nidn' => '0123456789',
        ]);

        $response = $this
            ->actingAs($mahasiswa)
            ->post(route('repository.store', 'magang'), [
                'program_studi_id' => $programStudi->id,
                'jenis_dokumen_id' => $jenisDokumen->id,
                'dosen_pembimbing_id' => $dosen->id,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->name,
                'email' => $mahasiswa->email,
                'tahun' => '2026',
                'bulan' => '7',
                'judul' => 'Sistem Informasi Magang',
                'tempat_magang' => 'PT Contoh',
                'jumlah_halaman' => 55,
                'file_dokumen' => UploadedFile::fake()->create('laporan.pdf', 120, 'application/pdf'),
                'file_project' => UploadedFile::fake()->create('project.zip', 256, 'application/zip'),
            ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));

        $document = RepositoryDocument::firstOrFail();

        $this->assertNotNull($document->file_project);
        Storage::disk('local')->assertExists($document->file_dokumen);
        Storage::disk('local')->assertExists($document->file_project);

        $approvalResponse = $this
            ->actingAs($dosen)
            ->patch(route('dosen.approvals.approve', $document), [
                'catatan_dosen' => 'Project sudah sesuai.',
            ]);

        $approvalResponse->assertRedirect();

        $this->assertDatabaseHas('repository_documents', [
            'id' => $document->id,
            'dosen_approved_by' => $dosen->id,
            'catatan_dosen' => 'Project sudah sesuai.',
        ]);
    }

    public function test_project_upload_must_be_zip_or_rar(): void
    {
        Storage::fake('local');

        $programStudi = ProgramStudi::create([
            'nama' => 'Sistem Informasi',
            'kode' => 'SI',
            'aktif' => true,
        ]);

        $dosen = User::create([
            'name' => 'Dosen Pembimbing',
            'email' => 'dosen.reject@test.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'status_akun' => 'aktif',
            'nidn' => '0123456789',
        ]);

        $mahasiswa = User::create([
            'name' => 'Mahasiswa Reject',
            'email' => 'mahasiswa.reject@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'status_akun' => 'aktif',
            'nim' => '123456789',
            'program_studi_id' => $programStudi->id,
        ]);

        $response = $this
            ->actingAs($mahasiswa)
            ->from(route('repository.create', 'magang'))
            ->post(route('repository.store', 'magang'), [
                'program_studi_id' => $programStudi->id,
                'dosen_pembimbing_id' => $dosen->id,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->name,
                'email' => $mahasiswa->email,
                'tahun' => '2026',
                'judul' => 'Project Salah Format',
                'file_dokumen' => UploadedFile::fake()->create('laporan.pdf', 120, 'application/pdf'),
                'file_project' => UploadedFile::fake()->create('project.exe', 20, 'application/octet-stream'),
            ]);

        $response->assertSessionHasErrors('file_project');
        $this->assertDatabaseCount('repository_documents', 0);
    }
}
