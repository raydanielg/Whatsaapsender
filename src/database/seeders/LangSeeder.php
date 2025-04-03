<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!(Language::where('code','us')->exists())){

            Language::create([
                'name'=> 'English',
                'code'=> 'us',
                'is_default'=> StatusEnum::TRUE->status(),
                'status'=> StatusEnum::TRUE->status(),
            ]);
        }
    }
}
