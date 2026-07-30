<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepositoryDocument extends Model
{
    protected $fillable = [
        'user_id',
        'program_studi_id',
        'jenis_dokumen_id',
        'kategori',
        'jenis_input',
        'input_by',
        'dosen_pembimbing_id',
        'nim',
        'nidn',
        'nama',
        'email',
        'tahun',
        'bulan',
        'judul',
        'tempat_magang',
        'jumlah_halaman',
        'abstrak',
        'detail',
        'submission_token',
        'file_dokumen',
        'file_project',
        'status',
        'status_penelitian',
        'tanggal_upload',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
        'dosen_approved_by',
        'dosen_approved_at',
        'catatan_dosen',
        // Bebas pustaka prerequisite fields (managed by admin)
        'hard_copy_submitted',
        'pdf_kelengkapan_confirmed',
        'has_active_loans',
        // Admin explicit permission to download kartu bebas pustaka
        'bebas_pustaka_diizinkan',
        'bebas_pustaka_diizinkan_by',
        'bebas_pustaka_diizinkan_at',
        // PDF upload completeness declaration by student
        'pdf_kelengkapan_deklarasi',
        'pdf_page_count',
    ];

    protected $casts = [
        'tanggal_upload'             => 'datetime',
        'verified_at'                => 'datetime',
        'dosen_approved_at'          => 'datetime',
        'bebas_pustaka_diizinkan_at' => 'datetime',
        'hard_copy_submitted'        => 'boolean',
        'pdf_kelengkapan_confirmed'  => 'boolean',
        'has_active_loans'           => 'boolean',
        'bebas_pustaka_diizinkan'    => 'boolean',
        'pdf_kelengkapan_deklarasi'  => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inputter()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    public function dosenApprover()
    {
        return $this->belongsTo(User::class, 'dosen_approved_by');
    }

    /**
     * Check whether all prerequisites are met to download the Kartu Bebas Pustaka.
     */
    public function canDownloadBebasPustaka(): bool
    {
        return count($this->bebasPustakaBlockers()) === 0;
    }

    /**
     * Return a list of unmet prerequisites for Kartu Bebas Pustaka.
     * Empty array means all requirements are met.
     */
    public function bebasPustakaBlockers(): array
    {
        $blockers = [];

        if ($this->has_active_loans) {
            $blockers[] = 'Masih ada peminjaman buku di perpustakaan yang belum dikembalikan.';
        }

        if (! $this->dosen_approved_at) {
            $blockers[] = 'Dokumen skripsi belum mendapat persetujuan (ACC) dari dosen pembimbing.';
        }

        if (! $this->pdf_kelengkapan_deklarasi) {
            $blockers[] = 'Soft copy PDF belum disertai deklarasi kelengkapan (scan halaman pengesahan, persetujuan, dan pernyataan orisinalitas).';
        }

        if (! $this->pdf_kelengkapan_confirmed) {
            $blockers[] = 'Kelengkapan halaman PDF (pengesahan + persetujuan + orisinalitas) belum diverifikasi oleh admin perpustakaan.';
        }

        if (! $this->hard_copy_submitted) {
            $blockers[] = 'Hard copy (buku jilid) belum diserahkan / dikonfirmasi oleh perpustakaan.';
        }

        if (! $this->bebas_pustaka_diizinkan) {
            $blockers[] = 'Admin perpustakaan belum memberikan izin download Kartu Bebas Pustaka.';
        }

        return $blockers;
    }

    /**
     * Relation to the admin who approved the bebas pustaka download.
     */
    public function bebasPustakaApprover()
    {
        return $this->belongsTo(\App\Models\User::class, 'bebas_pustaka_diizinkan_by');
    }
}

