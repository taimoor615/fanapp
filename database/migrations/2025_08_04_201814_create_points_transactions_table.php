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

        // Add points column to users table if it doesn't exist
        if (!Schema::hasColumn('users', 'total_points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('total_points')->default(0)->after('email_verified_at');
                $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null')->after('total_points');
            });
        }

        Schema::create('points_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('amount');
            $table->enum('type', ['earned', 'spent', 'adjusted']);
            $table->string('reason')->nullable();
            $table->string('related_type')->nullable(); // Model type (Game, Reward, etc.)
            $table->unsignedBigInteger('related_id')->nullable(); // Model ID
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['related_type', 'related_id']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('points_transactions');

        if (Schema::hasColumn('users', 'points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['total_points', 'team_id']);
            });
        }
    }
};
