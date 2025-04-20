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
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('ProfileID');
            $table->unsignedBigInteger('UserID'); // Foreign key column
            $table->string('University')->nullable();
            $table->string('GPA')->nullable(); // Corrected from GDP
            $table->text('Personal Description')->nullable(); // Using text for potentially longer descriptions
            $table->text('Technical Description')->nullable(); // Using text
            $table->string('Git Hyper Link')->nullable();
            $table->timestamps(); // Optional: if you want to track creation/update time

            // Foreign key constraint (adjust onDelete behavior as needed: cascade, set null, restrict)
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
