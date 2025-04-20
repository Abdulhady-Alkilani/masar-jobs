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
            // اجعل هذا العمود يقبل القيمة NULL
            $table->unsignedBigInteger('UserID')->nullable(); // <--- التعديل هنا
            $table->string('Course name');
            $table->string('Trainers name')->nullable();
            $table->text('Course Description')->nullable();
            $table->string('Site')->nullable();
            $table->string('Trainers Site')->nullable();
            $table->date('Start Date')->nullable();
            $table->date('End Date')->nullable();
            $table->string('Enroll Hyper Link')->nullable();
            $table->string('Stage')->nullable();
            $table->string('Certificate')->nullable();
            $table->timestamps();

            // الآن يمكن تطبيق هذا القيد بشكل صحيح
            $table->foreign('UserID')
                  ->references('UserID') // تأكد من أن المفتاح الرئيسي في جدول users هو UserID
                  ->on('users')
                  ->onDelete('set null'); // إذا حُذف المستخدم، اجعل UserID هنا NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        // من الجيد إزالة القيد قبل حذف الجدول (اختياري لكن ممارسة جيدة)
        Schema::table('training_courses', function (Blueprint $table) {
            // اسم القيد الافتراضي الذي يولده Laravel هو table_column_foreign
            $table->dropForeign(['UserID']); // أو $table->dropForeign('training_courses_userid_foreign');
        });
        Schema::dropIfExists('training_courses');
    }
};
