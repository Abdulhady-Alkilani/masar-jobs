<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء الـ Seeders بالترتيب المنطقي
        $this->call([
            UserSeeder::class,
            SkillSeeder::class,
            CompanySeeder::class,       // يعتمد على Users (مدير شركة)
            ProfileSeeder::class,       // يعتمد على Users (خريج/خبير)
            ArticleSeeder::class,       // يعتمد على Users (خبير)
            JobOpportunitySeeder::class,// يعتمد على Users (مدير شركة)
            TrainingCourseSeeder::class,// يعتمد على Users (خبير/مدير)
            GroupSeeder::class,
            // الجداول الوسيطة تأتي بعد الجداول الرئيسية التي تربطها
            UserSkillSeeder::class,       // يعتمد على Users و Skills
            JobApplicationSeeder::class,  // يعتمد على Users و JobOpportunities
            EnrollmentSeeder::class,      // يعتمد على Users و TrainingCourses
        ]);

        // يمكنك إزالة أو تعديل الكود الافتراضي الخاص بـ User::factory
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}