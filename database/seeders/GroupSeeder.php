<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::query()->delete(); // اختياري

        Group::create([
            'GroupID' => 1,
            'Telegram Hyper Link' => 'https://t.me/laravel_developers_ksa'
        ]);

        Group::create([
            'GroupID' => 2,
            'Telegram Hyper Link' => 'https://t.me/flutter_devs_middle_east'
        ]);

        Group::create([
            'GroupID' => 3,
            'Telegram Hyper Link' => 'https://t.me/job_opportunities_tech'
        ]);
    }
}