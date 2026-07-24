<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'admin@local.test')
            ->where('role', 'admin')
            ->delete();
    }

    public function down(): void
    {
        // The legacy admin account is intentionally not restored.
    }
};