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
            // Create user achievements table
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('achievement_type'); // attendance_streak, points_milestone, etc.
            $table->string('achievement_name');
            $table->text('description')->nullable();
            $table->integer('points_awarded')->default(0);
            $table->json('metadata')->nullable(); // Achievement-specific data
            $table->timestamp('earned_at');
            $table->timestamps();

            $table->unique(['user_id', 'achievement_type', 'achievement_name'], 'ua_user_type_name_unique');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
