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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->bigIncrements('ID'); // As per schema
            $table->unsignedBigInteger('UserID'); // User applying
            $table->unsignedBigInteger('JobID'); // Job applied for
            $table->string('Status')->nullable(); // Application status
            $table->date('Date'); // Application date (or use created_at)
            $table->text('Description')->nullable(); // Cover letter/notes
            $table->string('CV')->nullable(); // Path to CV file
            $table->timestamps();

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('JobID')->references('JobID')->on('job_opportunities')->onDelete('cascade');

            // Optional: Prevent double application
            $table->unique(['UserID', 'JobID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
