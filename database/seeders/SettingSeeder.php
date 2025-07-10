<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'key' => 'app_logo',
            'type' => 'image',
            'value' => 'logo.png',
        ]);
    }
}
