<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->enum('action', ['CREATED', 'UPDATED', 'DELETED', 'LOGIN', 'LOGOUT', 'FAILED_LOGIN', 'OTHER']);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('endpoint', 255)->nullable();
            $table->json('payload')->nullable();
            $table->enum('status', ['SUCCESS', 'FAILED']);
            $table->text('messages')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('created_by')->nullable()->constrained('user_accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
