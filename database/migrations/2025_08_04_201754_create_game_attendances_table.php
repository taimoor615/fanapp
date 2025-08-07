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
        Schema::create('game_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->timestamp('attended_at');
            $table->integer('points_earned')->default(0);
            $table->enum('verification_method', ['gps', 'qr_code', 'manual'])->default('manual');
            $table->json('verification_data')->nullable(); // Store GPS coords or QR data
            $table->unique(['user_id', 'game_id']); // Prevent duplicate attendance
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_attendances');
    }
};
