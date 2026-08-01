<?php

namespace Tests\Feature;

use App\Models\RepositoryDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositorySearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_letter_search_returns_titles_starting_with_that_letter_alphabetically(): void
    {
        RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nama' => 'Mahasiswa Satu',
            'tahun' => 2026,
            'judul' => 'Sistem Akademik Kampus',
            'status' => 'terverifikasi',
        ]);

        RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nama' => 'Mahasiswa Dua',
            'tahun' => 2026,
            'judul' => 'Aplikasi Perpustakaan Digital',
            'status' => 'terverifikasi',
        ]);

        RepositoryDocument::create([
            'kategori' => 'skripsi',
            'jenis_input' => 'upload',
            'nama' => 'Mahasiswa Tiga',
            'tahun' => 2026,
            'judul' => 'Analisis Sistem Informasi',
            'status' => 'terverifikasi',
        ]);

        $response = $this->get(route('repository.index', ['search' => 'A']));

        $response->assertOk();
        $response->assertDontSee('Sistem Akademik Kampus');
        $response->assertSeeInOrder([
            'Analisis Sistem Informasi',
            'Aplikasi Perpustakaan Digital',
        ]);
    }
}
