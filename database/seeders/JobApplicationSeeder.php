<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\JobOpportunity;
use Carbon\Carbon;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobApplication::query()->delete(); // اختياري

        $graduates = User::where('type', 'خريج')->get();
        $jobOpportunities = JobOpportunity::where('Status', 'مفعل')->get();
        $statuses = ['Pending', 'Reviewed', 'Shortlisted', 'Rejected']; // أمثلة لحالات الطلب

        if ($graduates->isEmpty() || $jobOpportunities->isEmpty()) {
            $this->command->warn('No graduates or active job opportunities found to create applications.');
            return;
        }

        foreach ($graduates as $graduate) {
            // جعل كل خريج يقدم على 1-2 فرصة عمل عشوائية
            $randomJobs = $jobOpportunities->random(min(rand(1, 2), $jobOpportunities->count()));
            foreach ($randomJobs as $job) {
                 // استخدام firstOrCreate لتجنب تقديم نفس الشخص مرتين لنفس الوظيفة
                 JobApplication::firstOrCreate(
                    [
                        'UserID' => $graduate->UserID,
                        'JobID' => $job->JobID,
                    ],
                    [
                        // 'ID' => ?, // إذا لم يكن auto-increment
                        'Status' => $statuses[array_rand($statuses)],
                        'Date' => Carbon::now()->subDays(rand(0, 5)), // تاريخ تقديم عشوائي خلال آخر 5 أيام
                        'Description' => 'Applied via seeder.',
                        'CV' => '/path/to/default_cv.pdf', // مسار افتراضي للسيرة الذاتية
                    ]
                );
            }
        }
    }
}