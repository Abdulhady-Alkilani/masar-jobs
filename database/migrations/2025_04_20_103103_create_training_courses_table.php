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
        Schema::create('training_courses', function (Blueprint $table) {
            $table->bigIncrements('CourseID');
            $table->unsignedBigInteger('UserID'); // User who created/manages
            $table->string('Course name'); // Corrected spelling
            $table->string('Trainers name')->nullable(); // Corrected spelling
            $table->text('Course Description')->nullable(); // Corrected spelling
            $table->string('Site')->nullable(); // (حضوري, اونلاين)
            $table->string('Trainers Site')->nullable(); // Corrected spelling (Training provider)
            $table->date('Start Date')->nullable(); // Corrected spelling
            $table->date('End Date')->nullable(); // Corrected spelling
            $table->string('Enroll Hyper Link')->nullable(); // Corrected spelling
            $table->string('Stage')->nullable(); // (مبتدئ, متوسط, متقدم)
            $table->string('Certificate')->nullable(); // (يوجد, لا يوجد) -> Consider boolean `has_certificate`
            // $table->boolean('has_certificate')->default(false); // Alternative
            $table->timestamps();

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('set null'); // Or cascade, restrict?
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_courses');
    }
};
