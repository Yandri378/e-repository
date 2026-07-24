<?php

namespace Database\Seeders;

use App\Models\JenisDokumen;
use App\Models\GuideTemplate;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $programStudi = [
            ['nama' => 'Sistem Informasi', 'kode' => 'SI'],
            ['nama' => 'Informatika', 'kode' => 'IF'],
            ['nama' => 'Manajemen Informatika', 'kode' => 'MI'],
            ['nama' => 'Teknik Informasi', 'kode' => 'TI'],
            ['nama' => 'Bisnis Digital', 'kode' => 'BD'],
            ['nama' => 'Desain Komunikasi Visual', 'kode' => 'DKV'],
            ['nama' => 'Manajemen Ritel', 'kode' => 'MR'],
        ];

        foreach ($programStudi as $prodi) {
            ProgramStudi::updateOrCreate(['kode' => $prodi['kode']], $prodi);
        }

        foreach ([
            ['nama' => 'Skripsi', 'kategori' => 'skripsi'],
            ['nama' => 'Laporan Magang', 'kategori' => 'magang'],
            ['nama' => 'PKM', 'kategori' => 'pkm'],
            ['nama' => 'Penelitian', 'kategori' => 'penelitian'],
            ['nama' => 'Panduan Repository', 'kategori' => 'panduan'],
        ] as $jenis) {
            JenisDokumen::firstOrCreate(['nama' => $jenis['nama']], $jenis);
        }

        foreach ([
            [
                'judul' => 'Panduan Upload Skripsi / TA',
                'kategori' => 'mahasiswa',
                'deskripsi' => 'Mahasiswa mengunggah file PDF skripsi atau tugas akhir melalui dashboard, mengisi identitas, program studi, tahun, judul, abstrak, lalu mengirim pemberitahuan WhatsApp ke admin untuk verifikasi.',
            ],
            [
                'judul' => 'Panduan Upload Laporan Magang',
                'kategori' => 'mahasiswa',
                'deskripsi' => 'Laporan magang wajib berformat PDF, mencantumkan tempat magang, tahun, jumlah halaman, dan data mahasiswa. Setelah upload, status dokumen akan menjadi pending sampai diverifikasi admin.',
            ],
            [
                'judul' => 'Panduan Upload PKM dan Penelitian Dosen',
                'kategori' => 'dosen',
                'deskripsi' => 'Dosen dapat mengunggah dokumen PKM dan penelitian dari dashboard dosen. Lengkapi NIDN, judul, tahun, status penelitian, abstrak, dan file PDF agar dokumen mudah ditinjau admin.',
            ],
            [
                'judul' => 'Panduan Verifikasi Admin',
                'kategori' => 'admin',
                'deskripsi' => 'Admin memeriksa akun baru, mengecek dokumen pending, membuka file PDF, lalu memilih konfirmasi atau tolak. Catatan verifikasi digunakan bila dokumen perlu diperbaiki.',
            ],
            [
                'judul' => 'Ketentuan File Repository',
                'kategori' => 'panduan',
                'deskripsi' => 'File yang diunggah harus PDF, maksimal 10 MB, menggunakan data identitas yang benar, dan tidak boleh berisi dokumen yang belum final atau tidak sesuai kategori.',
            ],
            [
                'judul' => 'Alur Status Dokumen',
                'kategori' => 'panduan',
                'deskripsi' => 'Status pending berarti menunggu admin, terverifikasi berarti sudah tampil di repository publik, ditolak berarti perlu perbaikan, dan arsip digunakan admin untuk data lama.',
            ],
        ] as $guide) {
            GuideTemplate::updateOrCreate(
                ['judul' => $guide['judul']],
                array_merge($guide, ['aktif' => true])
            );
        }

        User::firstOrCreate(['email' => 'admin@metamedia.ac.id'], [
            'name' => 'Administrator Repository',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);
    }
}
