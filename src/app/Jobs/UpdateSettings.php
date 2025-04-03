<?php

namespace App\Jobs;

use App\Service\Admin\Core\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSettings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $settings;
    protected $settingsService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->settingsService = new SettingService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->settingsService->updateSettings($this->settings);
    }
}
