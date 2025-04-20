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
        Schema::create('job_opportunities', function (Blueprint $table) {
            $table->bigIncrements('JobID');
            $table->unsignedBigInteger('UserID'); // User who posted
            $table->string('Job Title'); // Corrected spelling
            $table->text('Job Description'); // Corrected spelling
            $table->text('Qualification')->nullable();
            $table->string('Site')->nullable(); // Location
            $table->date('Date'); // Posting Date (or use created_at)
            $table->text('Skills')->nullable(); // Required skills (text, JSON, or relational)
            $table->string('Type'); // (تدريب, وظيفة)
            $table->date('End Date')->nullable(); // Application deadline
            $table->string('Status')->default('مفعل'); // (مفعل, معلق, محذوف)
            $table->timestamps();

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_opportunities');
    }
};
