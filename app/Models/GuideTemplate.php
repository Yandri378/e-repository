<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuideTemplate extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'kategori', 'file_path', 'uploaded_by', 'aktif'];
}
