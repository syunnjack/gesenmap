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
        Schema::create('location_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_center_id');
            $table->string('ip_hash', 64);
            $table->timestamps();

            $table->foreign('game_center_id')->references('id')->on('game_centers')->onDelete('cascade');
            $table->unique(['game_center_id', 'ip_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_votes');
    }
};
