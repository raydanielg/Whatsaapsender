<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use App\Models\SMSlog;
use App\Service\Admin\Dispatch\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send sms';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

  
    public function handle()
    {
       
    }
}
