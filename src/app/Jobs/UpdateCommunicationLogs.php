<?php

namespace App\Jobs;

use App\Models\CommunicationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCommunicationLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $communicationLogs;

    public function __construct($communicationLogs)
    {
        $this->communicationLogs = $communicationLogs;
    }

    public function handle()
    {
        foreach ($this->communicationLogs as $log) {
            
            CommunicationLog::create($log);
        }
    }
}
