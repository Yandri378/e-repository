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
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'verified_at' => 'datetime',
        'dosen_approved_at' => 'datetime',
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
}
