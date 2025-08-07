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
        Schema::create('rewards_catalog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('points_cost');
            $table->enum('reward_type', ['discount', 'merchandise', 'experience', 'tickets'])->default('discount');
            $table->integer('stock_quantity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('terms_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards_catalog');
    }
};
