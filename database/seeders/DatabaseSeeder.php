<?php

namespace Database\Seeders;

use App\Enums\SettingKyeTypeEnum;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
        ]);

        Currency::insert([
            ['code' => 'LYD', 'name' => 'دينار ليبي'],
            ['code' => 'EUR', 'name' => 'يورو'],
        ]);

        Setting::insert([
            ['key' => 'company_phone', 'value' => '', 'type' => SettingKyeTypeEnum::STRING],
            ['key' => 'company_address', 'value' => '', 'type' => SettingKyeTypeEnum::STRING],
        ]);
    }
}
