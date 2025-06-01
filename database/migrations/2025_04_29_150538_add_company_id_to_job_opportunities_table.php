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
        Schema::table('job_opportunities', function (Blueprint $table) {
            // !!! هام: تأكد أن نوع CompanyID هنا (unsignedBigInteger)
            // !!! يطابق نوع المفتاح الأساسي (CompanyID) في جدول companies
            $table->unsignedBigInteger('CompanyID')
                  ->nullable() // اجعله nullable لتجنب مشاكل البيانات الحالية
                  ->after('UserID'); // وضعه بعد UserID
    
            // !!! تأكد أن اسم جدول الشركات هو 'companies'
            // !!! وأن اسم المفتاح الأساسي فيه هو 'CompanyID'
            $table->foreign('CompanyID')
                  ->references('CompanyID')->on('companies')
                  ->onDelete('cascade'); // أو set null
        });
    }
    
    // تأكد أن دالة down() تحتوي على الكود العكسي الصحيح
    public function down(): void
    {
        Schema::table('job_opportunities', function (Blueprint $table) {
            $table->dropForeign(['CompanyID']);
            $table->dropColumn('CompanyID');
        });
    }
};
