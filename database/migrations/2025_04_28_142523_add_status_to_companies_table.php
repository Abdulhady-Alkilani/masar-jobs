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
        Schema::table('companies', function (Blueprint $table) {
            // أضف العمود status، اجعله نصيًا، يمكن أن يكون فارغًا مؤقتًا أو له قيمة افتراضية
            // القيمة الافتراضية 'pending' منطقية هنا
            // after(...) يحدد مكان إضافة العمود (اختياري)
            $table->string('status')->default('pending')->after('Web site'); // أو بعد أي عمود مناسب آخر
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // إزالة العمود عند التراجع عن الهجرة
            $table->dropColumn('status');
        });
    }
};