<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Skill::query()->delete(); // اختياري

        $skills = [
            ['Name' => 'PHP'],
            ['Name' => 'Laravel'],
            ['Name' => 'JavaScript'],
            ['Name' => 'Vue.js'],
            ['Name' => 'React'],
            ['Name' => 'MySQL'],
            ['Name' => 'Problem Solving'],
            ['Name' => 'Communication'],
            ['Name' => 'Teamwork'],
            ['Name' => 'Project Management'],
            ['Name' => 'Flutter'],
            ['Name' => 'REST API Design'],
            ['Name' => 'Git'],
        ];

        foreach ($skills as $skill) {
            // استخدام firstOrCreate لتجنب إنشاء نفس المهارة مرتين
            Skill::firstOrCreate($skill);
        }
    }
}