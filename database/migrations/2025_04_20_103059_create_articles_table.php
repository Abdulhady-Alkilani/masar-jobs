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
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('ArticleID'); // Corrected spelling
            $table->unsignedBigInteger('UserID');
            $table->string('Title'); // Corrected spelling
            $table->text('Description'); // Corrected spelling, using text
            $table->date('Date'); // Publication date
            $table->string('Type')->nullable(); // Article type (استشاري, نصائح)
            $table->string('Article Photo')->nullable(); // Path to photo
            $table->timestamps(); // Adds created_at and updated_at

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade'); // Or set null if user deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
