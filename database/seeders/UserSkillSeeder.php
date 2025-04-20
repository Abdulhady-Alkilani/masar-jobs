<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Support\Facades\DB; // لاستخدام DB::table

class UserSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // حذف الارتباطات القديمة (اختياري)
        DB::table('user_skills')->delete();

        $users = User::whereIn('type', ['خريج', 'خبير استشاري'])->get();
        $skills = Skill::all();
        $stages = ['مبتدئ', 'متوسط', 'متقدم'];

        if ($users->isEmpty() || $skills->isEmpty()) {
            $this->command->warn('No users or skills found to create relationships.');
            return;
        }

        foreach ($users as $user) {
            // ربط كل مستخدم بـ 3-5 مهارات عشوائية بمستوى عشوائي
            $randomSkills = $skills->random(rand(3, 5));
            foreach ($randomSkills as $skill) {
                // استخدام attach لإنشاء العلاقة في الجدول الوسيط
                $user->skills()->attach($skill->SkillID, ['Stage' => $stages[array_rand($stages)]]);
                // ملاحظة: هذا يتطلب تعريف علاقة skills() في نموذج User
                // أو يمكنك استخدام DB::table('user_skills')->insert([...]);
            }
        }
    }
}