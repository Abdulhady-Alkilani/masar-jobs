<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::query()->delete(); // اختياري

        // الحصول على مستخدم مدير الشركة الذي أنشأناه سابقاً
        $manager = User::where('type', 'مدير شركة')->first();

        if ($manager) {
            Company::create([
                'CompanyID' => 1,
                'UserID' => $manager->UserID,
                'Name' => 'Tech Solutions Inc.',
                'Email' => 'contact@techsolutions.com',
                'Phone' => '555123456',
                'Description' => 'A leading company in software development.',
                'Country' => 'Saudi Arabia',
                'City' => 'Riyadh',
                'Detailed Address' => '123 Tech Street, Olaya',
                'Web site' => 'https://techsolutions.com',
                // 'Media' => null, // يمكنك إضافة مسار لصور/فيديو هنا
            ]);

            // يمكنك إضافة المزيد من الشركات هنا، ربما لمدراء شركات آخرين إذا أنشأتهم
            // Company::factory()->count(5)->create(); // يتطلب CompanyFactory
        } else {
            $this->command->warn('No company manager user found to assign companies to.');
        }
    }
}