<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add gamification columns to users
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('preferred_language');
            $table->integer('streak_days')->default(0)->after('points');
            $table->date('last_activity_date')->nullable()->after('streak_days');
        });

        // Add difficulty column to exercises
        Schema::table('exercises', function (Blueprint $table) {
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium')->after('lesson_id');
        });

        // Create comments table
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');

        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'streak_days', 'last_activity_date']);
        });
    }
};
