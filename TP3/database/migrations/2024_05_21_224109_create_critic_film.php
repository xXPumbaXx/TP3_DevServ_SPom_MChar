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
        Schema::create('critic_film', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('critic_id')->constrained('critics')->onDelete('cascade');
            $table->foreignId('film_id')->constrained('films')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('critic_film');
    }
};
