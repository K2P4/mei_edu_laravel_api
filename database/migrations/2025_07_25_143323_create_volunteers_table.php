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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name');
            $table->string('email')->unique();
            $table->string('image', 512)->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->string('batch')->nullable();
            $table->string('dob')->nullable();
            $table->string('team')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
