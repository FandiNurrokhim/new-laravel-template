<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['HEADER', 'PARENT', 'SUB PARENT']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('header')->nullable();
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
