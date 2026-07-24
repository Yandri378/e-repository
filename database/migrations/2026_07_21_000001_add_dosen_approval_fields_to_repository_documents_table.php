<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_pembimbing_id')
                ->nullable()
                ->after('input_by')
                ->index();
            $table->unsignedBigInteger('dosen_approved_by')
                ->nullable()
                ->after('catatan_verifikasi')
                ->index();
            $table->timestamp('dosen_approved_at')->nullable()->after('dosen_approved_by');
            $table->text('catatan_dosen')->nullable()->after('dosen_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->dropIndex(['dosen_pembimbing_id']);
            $table->dropIndex(['dosen_approved_by']);
            $table->dropColumn([
                'dosen_pembimbing_id',
                'dosen_approved_by',
                'dosen_approved_at',
                'catatan_dosen',
            ]);
        });
    }
};
