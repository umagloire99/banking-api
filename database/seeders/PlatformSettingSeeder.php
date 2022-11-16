<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class PlatformSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Truncating platform_settings table');
        DB::table('platform_settings')->truncate();

        $this->command->info('Seeding platform_settings table');
        $settings = Valuestore::make(config_path('platform_settings.json'));
        foreach ($settings->all() as $key => $value) {
            PlatformSetting::create(['key' => $key, 'value' => $value]);
        }
    }
}
