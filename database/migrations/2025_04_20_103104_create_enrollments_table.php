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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->bigIncrements('EnrollmentID'); // As per schema, corrected spelling
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CourseID');
            $table->string('Status')->nullable(); // (مكتمل, قيد التقدم, ملغي)
            $table->date('Date'); // Enrollment date (or use created_at)
            $table->date('Complet Date')->nullable(); // Completion date, corrected spelling
            $table->timestamps(); // Tracks creation/update of the enrollment itself

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('CourseID')->references('CourseID')->on('training_courses')->onDelete('cascade');

            // Prevent enrolling in the same course twice
            $table->unique(['UserID', 'CourseID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
