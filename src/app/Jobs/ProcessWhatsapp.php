<?php

namespace App\Jobs;

use App\Enums\CommunicationStatusEnum;
use App\Enums\StatusEnum;
use App\Http\Utility\SendWhatsapp;
use App\Models\CommunicationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WhatsappLog;
use Exception;

class ProcessWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected CommunicationLog $whatsappLog){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $whatsappLog = $this->whatsappLog;

            if($whatsappLog->whatsappGateway->type == StatusEnum::FALSE->status() && $whatsappLog->status != CommunicationStatusEnum::FAIL->value) {
                
                SendWhatsapp::sendNodeMessages($whatsappLog, null);
                
            } else {
                
                SendWhatsapp::sendCloudApiMessages($whatsappLog, null);
            }
        } catch (\Exception $exception) {
            
            \Log::error("Process whatsapp failed: " . $exception->getMessage());
        }
    }
}
