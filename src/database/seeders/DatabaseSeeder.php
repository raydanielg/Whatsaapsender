<?php

namespace Database\Seeders;

use Database\Seeders\LangSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\TemplatesTableSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingsSeeder::class,
            // LangSeeder::class,
            // TemplatesTableSeeder::class
        ]);
    }
}
