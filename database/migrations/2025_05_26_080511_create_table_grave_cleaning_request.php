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
        Schema::create('grave_cleaning_request', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('requester_name'); 
            $table->string('proof_photo')->nullable();
            $table->integer('price')->default(15000); 
            $table->string('phone_number'); 
            $table->string('address'); 
            $table->string('rt')->nullable(); 
            $table->string('rw')->nullable(); 
            $table->string('dusun')->nullable(); 
            $table->foreignId('grave_location_id')->constrained()->onDelete('cascade');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid'])->default('unpaid'); 
            $table->enum('work_status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grave_cleaning_request');
    }
};
