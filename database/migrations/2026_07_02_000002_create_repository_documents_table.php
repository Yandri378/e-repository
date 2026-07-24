<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repository_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studi')->nullOnDelete();
            $table->foreignId('jenis_dokumen_id')->nullable()->constrained('jenis_dokumen')->nullOnDelete();
            $table->enum('kategori', ['skripsi', 'magang', 'pkm', 'penelitian']);
            $table->enum('jenis_input', ['arsip', 'upload'])->default('upload');
            $table->foreignId('input_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nim', 30)->nullable();
            $table->string('nidn', 30)->nullable();
            $table->string('nama');
            $table->year('tahun');
            $table->unsignedTinyInteger('bulan')->nullable();
            $table->string('judul');
            $table->string('tempat_magang')->nullable();
            $table->unsignedInteger('jumlah_halaman')->nullable();
            $table->text('abstrak')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->enum('status', ['pending', 'terverifikasi', 'arsip', 'ditolak'])->default('pending');
            $table->enum('status_penelitian', ['berjalan', 'selesai'])->nullable();
            $table->timestamp('tanggal_upload')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repository_documents');
    }
};
