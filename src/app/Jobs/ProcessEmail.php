<?php

namespace App\Jobs;

use App\Enums\CommunicationStatusEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Utility\SendEmail;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Dispatch\EmailService;
use Illuminate\Support\Facades\Log;
use SendGrid\Mail\TypeException;


class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailLog;
    protected $gateway;
    protected $emailService;
    protected $customerSevice;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailLog, $gateway){
        $this->gateway = $gateway;
        $this->emailLog = $emailLog;
        $this->customerSevice = new CustomerService;
        $this->emailService = new EmailService($this->customerSevice);
    }


    /**
     * @return void
     * @throws TypeException
     */
    public function handle(): void
    {
        try {
            
            if ($this->emailLog->status != CommunicationStatusEnum::FAIL->value) {

                $emailMethod = $this->gateway;
                $emailFrom   = $emailMethod->address;
                list($subject, $message, $email_to, $email_from_name, $email_reply_to) = $this->emailService->getEmailData($this->emailLog, $emailMethod);
                
    
                if($emailMethod->type == 'smtp') {

                    SendEmail::sendSMTPMail($email_to, $email_reply_to, $subject, $message, $this->emailLog,  $emailMethod, $email_from_name);
                }
                elseif($emailMethod->type == "mailjet") {
                    
                    SendEmail::sendMailJetMail($email_to, $subject, $message, $this->emailLog, $emailMethod, $email_reply_to, $email_from_name);
                }
                elseif($emailMethod->type == "aws") {
                    SendEmail::sendSesMail([$email_to], $subject, $message, $this->emailLog, $emailMethod); 
                }
                elseif($emailMethod->type  == "mailgun") {
                    
                    SendEmail::sendMailGunMail($email_to, $subject, $message, $this->emailLog, $emailMethod); 
                }
                elseif($emailMethod->type == "sendgrid") {
                    
                    SendEmail::sendGrid($emailFrom, $email_from_name, $email_to, $subject, $message, $this->emailLog, @$emailMethod->mail_gateways->secret_key);
                }
            }
        } catch(\Exception $exception) {
            Log::error("Process Email failed: " . $exception->getMessage());
        }
    }
}
