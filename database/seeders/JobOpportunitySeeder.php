<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobOpportunity;
use App\Models\User;
use Carbon\Carbon;

class JobOpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobOpportunity::query()->delete(); // اختياري

        $manager = User::where('type', 'مدير شركة')->first();

        if ($manager) {
            JobOpportunity::create([
                'JobID' => 1,
                'UserID' => $manager->UserID,
                'Job Title' => 'Junior Web Developer',
                'Job Description' => 'Seeking a motivated junior developer to join our team...',
                'Qualification' => 'Bachelor\'s degree in CS or related field, basic knowledge of PHP/JS.',
                'Site' => 'Riyadh (On-site)',
                'Date' => Carbon::now()->subDays(7),
                'Skills' => 'PHP, Laravel, JavaScript, MySQL, HTML, CSS', // يمكن أن يكون نصاً أو JSON
                'Type' => 'وظيفة', // أو 'تدريب'
                'End Date' => Carbon::now()->addDays(30), // تاريخ انتهاء التقديم
                'Status' => 'مفعل',
            ]);

            JobOpportunity::create([
                'JobID' => 2,
                'UserID' => $manager->UserID,
                'Job Title' => 'Flutter Development Internship',
                'Job Description' => 'Internship opportunity for students interested in mobile development using Flutter.',
                'Qualification' => 'Currently enrolled student, basic programming knowledge.',
                'Site' => 'Remote',
                'Date' => Carbon::now()->subDays(3),
                'Skills' => 'Flutter, Dart, Git',
                'Type' => 'تدريب',
                'End Date' => Carbon::now()->addDays(20),
                'Status' => 'مفعل',
            ]);

             // JobOpportunity::factory()->count(10)->create(); // يتطلب JobOpportunityFactory
        } else {
             $this->command->warn('No company manager user found to assign job opportunities to.');
        }
    }
}