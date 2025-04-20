<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\TrainingCourse;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Enrollment::query()->delete(); // اختياري

        $graduates = User::where('type', 'خريج')->get();
        $courses = TrainingCourse::all();
        $statuses = ['مكتمل', 'قيد التقدم', 'ملغي'];

         if ($graduates->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('No graduates or training courses found to create enrollments.');
            return;
        }

        foreach ($graduates as $graduate) {
            // تسجيل كل خريج في 1-2 دورة عشوائية
             $randomCourses = $courses->random(min(rand(1, 2), $courses->count()));
             foreach ($randomCourses as $course) {
                $status = $statuses[array_rand($statuses)];
                $completionDate = ($status === 'مكتمل') ? Carbon::now()->subDays(rand(1, 30)) : null;
                $enrollmentDate = $completionDate ? $completionDate->copy()->subDays(rand(30, 60)) : Carbon::now()->subDays(rand(5, 50));

                Enrollment::firstOrCreate(
                    [
                        'UserID' => $graduate->UserID,
                        'CourseID' => $course->CourseID,
                    ],
                    [
                        // 'EnrollmentID' => ?, // إذا لم يكن auto-increment
                        'Status' => $status,
                        'Date' => $enrollmentDate,
                        'Complet Date' => $completionDate,
                    ]
                );
             }
        }
    }
}