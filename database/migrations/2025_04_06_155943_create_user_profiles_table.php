<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->text('profile_picture_url')->nullable();
            $table->text('bio')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('language_preference', 10)->nullable();

            $table->timestamp('last_active')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
