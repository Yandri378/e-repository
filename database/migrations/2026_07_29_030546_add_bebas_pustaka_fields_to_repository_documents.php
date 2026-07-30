<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            // Admin confirms student has physically submitted hard copy (book) to the library
            $table->boolean('hard_copy_submitted')->default(false)->after('catatan_dosen');

            // Admin confirms uploaded PDF is complete with required scanned pages:
            // halaman pengesahan, halaman persetujuan, pernyataan orisinalitas
            $table->boolean('pdf_kelengkapan_confirmed')->default(false)->after('hard_copy_submitted');

            // Admin flags if student still has active book loans (true = still has loans, cannot download)
            $table->boolean('has_active_loans')->default(false)->after('pdf_kelengkapan_confirmed');
        });
    }

    public function down(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->dropColumn(['hard_copy_submitted', 'pdf_kelengkapan_confirmed', 'has_active_loans']);
        });
    }
};

