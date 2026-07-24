<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('repository_documents', 'email')) {
                $table->string('email')->nullable()->after('nama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('repository_documents', function (Blueprint $table) {
            if (Schema::hasColumn('repository_documents', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
