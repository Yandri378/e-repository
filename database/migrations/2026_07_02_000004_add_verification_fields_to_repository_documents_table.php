<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->foreignId('verified_by')->nullable()->after('tanggal_upload')->constrained('users')->nullOnDelete();
            $table->text('catatan_verifikasi')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['verified_by', 'catatan_verifikasi']);
        });
    }
};
