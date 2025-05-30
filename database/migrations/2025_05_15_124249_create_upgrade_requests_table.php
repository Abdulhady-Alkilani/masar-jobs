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
        Schema::create('upgrade_requests', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('UserID')->constrained('users', 'UserID')->onDelete('cascade'); // FK to users table
            $table->string('requested_role'); // e.g., 'خبير استشاري', 'مدير شركة'
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('reason')->nullable(); // Optional: Reason from user for upgrade
            $table->text('admin_notes')->nullable(); // Optional: Notes from admin
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgrade_requests');
    }
};