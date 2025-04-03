<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\StatusEnum;
class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingSetup = Setting::pluck('key')->toArray();
        $settings = config('site_settings');
        $formatedSettings = [];
        foreach($settings as $key=>$value){
            if(!in_array($key,$existingSetup)){
                array_push($formatedSettings , array(
                    'key'   => $key,
                    'value' => $value,
                    'uid'   => str_unique()
                ));
            }
        }

        Setting::insert($formatedSettings);
        optimize_clear();
    }
}
