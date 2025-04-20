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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->bigIncrements('id'); // Simple auto-incrementing ID for the pivot record itself
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('SkillID');
            $table->string('Stage')->nullable(); // Skill level
            // $table->timestamps(); // Optional: if you want track when the skill was added/updated

            // Foreign key constraints
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('SkillID')->references('SkillID')->on('skills')->onDelete('cascade');

            // Ensure a user doesn't have the same skill listed twice
            $table->unique(['UserID', 'SkillID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
