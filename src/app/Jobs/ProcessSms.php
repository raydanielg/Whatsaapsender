<?php

namespace App\Jobs;

use App\Enums\CommunicationStatusEnum;
use App\Models\SmsGateway;
use App\Service\Admin\Dispatch\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Utility\SendSMS;
use App\Models\CommunicationLog;
use App\Models\SMSlog;
use App\Models\CreditLog;
use App\Models\Gateway;
use App\Models\User;
use App\Models\GeneralSetting;
use Exception;

class ProcessSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct(protected  CommunicationLog $SMSlog, protected Gateway $gateway){}
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $api_method = transformToCamelCase($this->gateway->type);
            SendSMS::$api_method($this->SMSlog->meta_data['contact'], $this->SMSlog->meta_data['sms_type'], $this->SMSlog->message['message_body'], (object)$this->gateway->sms_gateways, $this->SMSlog->id);
          
        } catch (\Exception $exception) {
            \Log::error("ProcessSms failed: " . $exception->getMessage());
        }
    }
}
