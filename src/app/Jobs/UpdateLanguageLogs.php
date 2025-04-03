<?php

namespace App\Jobs;

use App\Models\Language;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateLanguageLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $languages;

    public function __construct($languages)
    {
        $this->languages = $languages;
    }

    public function handle()
    {
        foreach ($this->languages as $language) {
            Language::create($language);
        }
    }
}
