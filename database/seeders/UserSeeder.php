<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // حذف المستخدمين القدامى (اختياري، لتجنب التكرار عند إعادة التشغيل)
        User::query()->delete();

        // 1. إنشاء مستخدم Admin
        User::create([
            'UserID' => 1, // يمكنك جعلها auto-increment إذا أردت
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // استخدم كلمة مرور قوية!
            'email_verified' => true,
            'phone' => '111111111',
            'status' => 'مفعل',
            'type' => 'Admin',
            // created_at و updated_at سيتم تعيينهما تلقائيًا
        ]);

        // 2. إنشاء مستخدم مدير شركة
        User::create([
            'UserID' => 2,
            'first_name' => 'Company',
            'last_name' => 'Manager',
            'username' => 'companymanager',
            'email' => 'manager@company.com',
            'password' => Hash::make('password'),
            'email_verified' => true,
            'phone' => '222222222',
            'status' => 'مفعل',
            'type' => 'مدير شركة',
        ]);

        // 3. إنشاء مستخدم خبير استشاري
        User::create([
            'UserID' => 3,
            'first_name' => 'Expert',
            'last_name' => 'Consultant',
            'username' => 'expertconsultant',
            'email' => 'expert@consultant.com',
            'password' => Hash::make('password'),
            'email_verified' => true,
            'phone' => '333333333',
            'status' => 'مفعل',
            'type' => 'خبير استشاري',
        ]);

        // 4. إنشاء مستخدم خريج
        User::create([
            'UserID' => 4,
            'first_name' => 'Graduate',
            'last_name' => 'Student',
            'username' => 'graduatestudent',
            'email' => 'graduate@student.com',
            'password' => Hash::make('password'),
            'email_verified' => true,
            'phone' => '444444444',
            'status' => 'مفعل',
            'type' => 'خريج',
        ]);

        // يمكنك إضافة المزيد من المستخدمين هنا أو استخدام Factories
        // User::factory()->count(20)->create(); // يتطلب إنشاء UserFactory
    }
}