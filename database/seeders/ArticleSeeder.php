<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::query()->delete(); // اختياري

        $expert = User::where('type', 'خبير استشاري')->first();

        if ($expert) {
            Article::create([
                'ArticleID' => 1,
                'UserID' => $expert->UserID,
                'Title' => 'أهمية بناء ملف شخصي قوي للخريجين',
                'Description' => 'نص المقال هنا يتحدث عن كيفية بناء ملف شخصي جذاب لأصحاب العمل...',
                'Date' => Carbon::now()->subDays(5), // تاريخ النشر قبل 5 أيام
                'Type' => 'نصائح', // أو 'استشاري'
                'Article Photo' => null, // مسار الصورة إن وجد
            ]);

            Article::create([
                'ArticleID' => 2,
                'UserID' => $expert->UserID,
                'Title' => 'الاتجاهات الحديثة في تطوير الويب لعام 2024',
                'Description' => 'استعراض لأحدث التقنيات والأطر في عالم تطوير الويب...',
                'Date' => Carbon::now()->subDays(2),
                'Type' => 'استشاري',
                'Article Photo' => null,
            ]);

            // Article::factory()->count(15)->create(); // يتطلب ArticleFactory
        } else {
             $this->command->warn('No expert consultant user found to assign articles to.');
        }
    }
}