<?php

namespace App\Console\Commands;

use App\Jobs\ProcessWhatsapp;
use App\Models\CampaignContact;
use App\Models\User;
use App\Models\WhatsappCreditLog;
use App\Models\WhatsappLog;
use App\Service\Admin\Dispatch\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Whatsapp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @param WhatsAppService $whatsAppService
     * @return void
     */
    public function handle(WhatsAppService $whatsAppService)
    {
       
    }
}
