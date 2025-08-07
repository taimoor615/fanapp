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
        Schema::table('trivia_questions', function (Blueprint $table) {
            $table->enum('type', ['multiple_choice', 'true_false', 'text'])->default('multiple_choice')->after('question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trivia_questions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
