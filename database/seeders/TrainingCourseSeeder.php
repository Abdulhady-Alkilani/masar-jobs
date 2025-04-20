<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingCourse;
use App\Models\User;
use Carbon\Carbon;

class TrainingCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TrainingCourse::query()->delete(); // اختياري

        // يمكن للخبير أو مدير الشركة إنشاء دورة
        $creator = User::whereIn('type', ['خبير استشاري', 'مدير شركة'])->first();

        if ($creator) {
            TrainingCourse::create([
                'CourseID' => 1,
                'UserID' => $creator->UserID,
                'Course name' => 'Introduction to Laravel 11',
                'Trainers name' => 'Dr. Expert Consultant',
                'Course Description' => 'A comprehensive introduction to the Laravel framework.',
                'Site' => 'اونلاين',
                'Trainers Site' => 'Expert Consulting Platform',
                'Start Date' => Carbon::now()->addDays(10),
                'End Date' => Carbon::now()->addDays(40),
                'Enroll Hyper Link' => 'https://enroll.expert.com/laravel11',
                'Stage' => 'مبتدئ',
                'Certificate' => 'يوجد', // أو true إذا كان boolean
            ]);

             TrainingCourse::create([
                'CourseID' => 2,
                'UserID' => $creator->UserID,
                'Course name' => 'Advanced Git Techniques',
                'Trainers name' => 'Mr. Tech Lead',
                'Course Description' => 'Master advanced Git workflows and collaboration techniques.',
                'Site' => 'اونلاين',
                'Trainers Site' => 'Tech Solutions Academy',
                'Start Date' => Carbon::now()->addDays(15),
                'End Date' => Carbon::now()->addDays(25),
                'Enroll Hyper Link' => 'https://enroll.techsolutions.com/git-adv',
                'Stage' => 'متقدم',
                'Certificate' => 'يوجد',
            ]);

            // TrainingCourse::factory()->count(8)->create(); // يتطلب TrainingCourseFactory
        } else {
             $this->command->warn('No suitable user (expert/manager) found to create training courses.');
        }
    }
}