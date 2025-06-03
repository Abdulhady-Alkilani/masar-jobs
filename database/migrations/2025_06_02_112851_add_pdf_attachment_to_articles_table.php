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
    Schema::table('articles', function (Blueprint $table) {
        // تأكد من نوع العمود (string مناسب للمسار)
        $table->string('pdf_attachment')->nullable()->after('Article Photo'); // أو بعد أي عمود آخر
    });
}

// تأكد أن دالة down() تحتوي على الكود العكسي الصحيح
public function down(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->dropColumn('pdf_attachment');
    });
}
};
