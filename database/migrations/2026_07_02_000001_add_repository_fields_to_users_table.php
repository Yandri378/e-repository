<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim', 30)->nullable()->unique()->after('email');
            $table->string('nidn', 30)->nullable()->unique()->after('nim');
            $table->foreignId('program_studi_id')->nullable()->after('nidn')->constrained('program_studi')->nullOnDelete();
            $table->string('whatsapp', 25)->nullable()->after('program_studi_id');
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->default('mahasiswa')->after('whatsapp');
            $table->enum('status_akun', ['menunggu_verifikasi', 'aktif', 'ditolak', 'nonaktif'])->default('menunggu_verifikasi')->after('role');
            $table->text('alasan_penolakan')->nullable()->after('status_akun');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_studi_id']);
            $table->dropColumn(['nim', 'nidn', 'program_studi_id', 'whatsapp', 'role', 'status_akun', 'alasan_penolakan']);
        });
    }
};
