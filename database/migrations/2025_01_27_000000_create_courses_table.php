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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->string('image', 512)->nullable();
            $table->unsignedBigInteger('volunteer_id');
            $table->foreign('volunteer_id')
                ->references('id')
                ->on('volunteers')
                ->onDelete('cascade');
            $table->string('duration')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
