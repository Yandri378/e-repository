<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nama');
            $table->text('detail')->nullable()->after('abstrak');
            $table->string('submission_token', 64)->nullable()->unique()->after('detail');
        });
    }

    public function down(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            $table->dropUnique(['submission_token']);
            $table->dropColumn(['email', 'detail', 'submission_token']);
        });
    }
};
