<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE repository_documents MODIFY jenis_input ENUM('arsip', 'upload', 'admin_import') NOT NULL DEFAULT 'upload'");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::table('repository_documents')
                ->where('jenis_input', 'admin_import')
                ->update(['jenis_input' => 'arsip']);

            DB::statement("ALTER TABLE repository_documents MODIFY jenis_input ENUM('arsip', 'upload') NOT NULL DEFAULT 'upload'");
        }
    }
};
