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
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('CompanyID');
            $table->unsignedBigInteger('UserID')->unique(); // Assuming one user manages one company
            $table->string('Name');
            $table->string('Email')->nullable();
            $table->string('Phone')->nullable();
            $table->text('Description')->nullable();
            $table->string('Country')->nullable(); // Corrected spelling
            $table->string('City')->nullable();
            $table->string('Detailed Address')->nullable(); // Corrected spelling
            $table->text('Media')->nullable(); // For multiple photos/videos, consider JSON or separate table
            $table->string('Web site')->nullable(); // Corrected spelling
            $table->timestamps();

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
