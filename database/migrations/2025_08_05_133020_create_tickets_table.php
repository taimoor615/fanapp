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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->enum('ticket_type', ['general', 'vip', 'season', 'complimentary']);
            $table->decimal('price', 8, 2);
            $table->enum('purchase_method', ['app', 'external', 'admin']);
            $table->enum('status', ['pending', 'confirmed', 'used', 'refunded', 'cancelled']);
            $table->string('seat_section')->nullable();
            $table->string('seat_row')->nullable();
            $table->string('seat_number')->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->json('purchase_data')->nullable(); // Payment info, external ticket ID, etc.
            $table->timestamp('purchased_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'game_id']);
            $table->index(['status', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
