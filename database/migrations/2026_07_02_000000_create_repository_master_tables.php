<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_studi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode', 20)->nullable()->unique();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('jenis_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['skripsi', 'magang', 'pkm', 'penelitian', 'panduan']);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_dokumen');
        Schema::dropIfExists('program_studi');
    }
};
