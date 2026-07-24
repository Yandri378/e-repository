<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('download_requests', function (Blueprint $table) {
            $table->string('requester_phone')->nullable()->after('requester_email');
        });
    }

    public function down()
    {
        Schema::table('download_requests', function (Blueprint $table) {
            $table->dropColumn('requester_phone');
        });
    }
};
