<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            // Admin explicitly grants permission to download the Kartu Bebas Pustaka
            $table->boolean('bebas_pustaka_diizinkan')->default(false)->after('has_active_loans');
            $table->unsignedBigInteger('bebas_pustaka_diizinkan_by')->nullable()->after('bebas_pustaka_diizinkan');
            $table->timestamp('bebas_pustaka_diizinkan_at')->nullable()->after('bebas_pustaka_diizinkan_by');

            // Student PDF upload declaration: declares PDF has required scanned pages
            $table->boolean('pdf_kelengkapan_deklarasi')->default(false)->after('bebas_pustaka_diizinkan_at');

            // System-detected page count in the uploaded PDF (for basic validation)
            $table->unsignedSmallInteger('pdf_page_count')->nullable()->after('pdf_kelengkapan_deklarasi');
        });
    }

    public function down(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->dropColumn([
                'bebas_pustaka_diizinkan',
                'bebas_pustaka_diizinkan_by',
                'bebas_pustaka_diizinkan_at',
                'pdf_kelengkapan_deklarasi',
                'pdf_page_count',
            ]);
        });
    }
};

