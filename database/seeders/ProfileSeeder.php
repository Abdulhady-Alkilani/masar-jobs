<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::query()->delete(); // اختياري

        $graduate = User::where('type', 'خريج')->first();
        $expert = User::where('type', 'خبير استشاري')->first();

        if ($graduate) {
            Profile::create([
                'ProfileID' => 1,
                'UserID' => $graduate->UserID,
                'University' => 'King Saud University',
                'GPA' => '4.5', // قد يكون من الأفضل استخدام float إذا كانت قيمة رقمية
                'Personal Description' => 'Enthusiastic computer science graduate seeking opportunities.',
                'Technical Description' => 'Proficient in web development technologies.',
                'Git Hyper Link' => 'https://github.com/graduatestudent',
            ]);
        }

        if ($expert) {
             Profile::create([
                'ProfileID' => 2,
                'UserID' => $expert->UserID,
                'University' => 'Stanford University', // مثال
                'GPA' => '3.9',
                'Personal Description' => 'Experienced consultant in software architecture.',
                'Technical Description' => 'Specializing in scalable backend systems and cloud infrastructure.',
                'Git Hyper Link' => 'https://github.com/expertconsultant',
            ]);
        }

        // يمكنك إضافة المزيد أو استخدام Factories
        // Profile::factory()->count(10)->create(); // يتطلب ProfileFactory وربطه بمستخدمين موجودين
    }
}