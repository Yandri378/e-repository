<?php

namespace Database\Seeders;

use App\Models\RepositorySetting;
use Illuminate\Database\Seeder;

class AdminSettingsSeeder extends Seeder
{
    public function run()
    {
        RepositorySetting::updateOrCreate(
            ['key' => 'admin_whatsapp'],
            ['value' => '6285363097108']
        );
    }
}
