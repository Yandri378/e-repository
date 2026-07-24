<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('download_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_document_id')->constrained('repository_documents')->onDelete('cascade');
            $table->string('submission_token')->nullable();
            $table->string('requester_email');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'approved', 'denied'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('download_requests');
    }
};
