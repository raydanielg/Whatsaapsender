<?php

namespace App\Jobs;

use App\Models\CreditLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCreditLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $creditLogs;

    public function __construct($creditLogs)
    {
        $this->creditLogs = $creditLogs;
    }

    public function handle()
    {
        foreach ($this->creditLogs as $log) {
            CreditLog::create($log);
        }
    }
}
