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
            // إضافة عمود Status (نوع string مناسب للحالات النصية)
            // حدد القيمة الافتراضية ('Pending' مثلاً عند إنشاء طلب جديد)
            // وضعه بعد عمود معين أو في النهاية
            $table->string('Status', 50)->default('Pending')->after('Web site'); // يمكنك تغيير الطول والقيمة الافتراضية والمكان حسب الحاجة

            // يمكنك إضافة index على هذا العمود إذا كنت ستقوم بالبحث بناءً عليه كثيرًا
            // $table->index('Status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('Status');
            // يمكنك إزالة الـ index إذا أضفته
            // $table->dropIndex(['Status']);
        });
    }
};

