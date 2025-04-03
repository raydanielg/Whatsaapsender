<?php

namespace App\Http\Controllers;

use App\Enums\AndroidApiSimEnum;
use App\Enums\CampaignRepeatEnum;
use App\Enums\CampaignStatusEnum;
use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Enums\SubscriptionStatus;
use App\Jobs\ProcessEmail;
use App\Jobs\ProcessSms;
use App\Jobs\ProcessWhatsapp;
use App\Models\AndroidApi;
use App\Models\AndroidApiSimInfo;
use App\Models\Campaign;
use App\Models\CampaignUnsubscribe;
use App\Models\CommunicationLog;
use App\Models\Gateway;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WhatsappDevice;
use Carbon\Carbon;
use App\Service\Admin\Core\SettingService;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Dispatch\EmailService;
use App\Service\Admin\Dispatch\SmsService;
use App\Service\Admin\Dispatch\WhatsAppService;
use Aws\Api\Service;
use Throwable;

class CronController extends Controller
{
    public SettingService $settingService;
    public CustomerService $customerService;
    
    public function __construct() {
        
        $this->settingService = new SettingService;
        $this->customerService = new CustomerService;    
    }

    public function run(): void {
		
        $this->settingService->updateSettings([
            "last_cron_run" => Carbon::now()
        ]);

        //Schedule Dispatches
        $this->smsApiSchedule();
        $this->smsAndroidSchedule();
        $this->whatsappSchedule();
        $this->emailSchedule();

        //Campaigns
        $this->processActiveCampagin();
        $this->processOngoingCampagin();
        $this->processCompletedCampagin();

        //Android gateway update
        $this->updateAndroidGateway();

        //Plan Expiration Check
        $this->checkPlanExpiration();
    }

    //Schedule Dispatches
    protected function smsApiSchedule(): void {
        
        try {
            $pass    = true;
            $user    = null;
            $gateway = null;	
            $smsLogs = CommunicationLog::where('type', ServiceType::SMS->value)
                                            ->where('status', CommunicationStatusEnum::SCHEDULE->value)
                                            ->whereNull("android_gateway_sim_id")
                                            ->whereNotNull("schedule_at")
                                            ->get();
        
            $has_daily_limit = true;
            foreach($smsLogs as $smsLog) {

                if($smsLog->user_id) {

                    $user = User::where("id", $smsLog->user_id)->first();
                    $pass = checkCredit($user, strtolower(ServiceType::getValue($smsLog->type)));
                    
                    if($user) {
                    
                        $allowed_access = planAccess($user);
                        if(count($allowed_access) > 0) {
                            $allowed_access = (object) planAccess($user);
                            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value);
                            $pass = $has_daily_limit;
                        } else {
                            $pass = false;
                        }
                        
                    } else {
                        $pass = false;
                    }
                    
                }
                if($pass && $smsLog->schedule_at && Carbon::now()->greaterThanOrEqualTo($smsLog->schedule_at)) {
                    
                    if($smsLog->gateway_id) {
                        $gateway = Gateway::where("id", $smsLog->gateway_id)->first();
                    }
                    
                
                    if($gateway) {

                        $smsLog->status = CommunicationStatusEnum::PROCESSING->value;
                        ProcessSms::dispatch($smsLog, $gateway);

                    } else {

                        if($user) {
                        
                            SmsService::updateSMSLogAndCredit($smsLog, CommunicationStatusEnum::FAIL->value, translate("Choosen Gateway: ").$smsLog->meta_data['gateway_name'].translate(" could not be found"));
                        } else {

                            $smsLog->status = CommunicationStatusEnum::FAIL->value;
                            $smsLog->response_message = translate("Choosen Gateway: ").$smsLog->meta_data['gateway_name'].translate(" could not be found");
                        }
                    }
                    $smsLog->save();
                }
            }
        } catch(Throwable $th) {}
    }
    
    protected function smsAndroidSchedule(): void {

        try {
            $pass    = true;
            $gateway = null;	
            $smsLogs = CommunicationLog::where('type', ServiceType::SMS->value)
                                            ->where('status', (string)CommunicationStatusEnum::SCHEDULE->value)
                                            ->whereNotNull("android_gateway_sim_id")
                                            ->whereNotNull("schedule_at")
                                            ->get();
        
            $has_daily_limit = true;
            foreach($smsLogs as $smsLog) {

                if($smsLog->user_id) {

                    $user = User::where("id", $smsLog->user_id)->first();
                    $pass = checkCredit($user, strtolower(ServiceType::getValue($smsLog->type)));
                    if($user) {
                    
                        $allowed_access = planAccess($user);
                        if(count($allowed_access) > 0) {
                            $allowed_access = (object) planAccess($user);
                            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value);
                            $pass = $has_daily_limit;
                        } else {
                            $pass = false;
                        }
                        
                    } else {
                        $pass = false;
                    }
                }
                if($pass && $smsLog->schedule_at && Carbon::now()->greaterThanOrEqualTo($smsLog->schedule_at)) {

                    $smsLog->status = CommunicationStatusEnum::PENDING->value;
                    $smsLog->save();
                }
            }
        } catch (Throwable $th){}
    }

    protected function whatsappSchedule(): void {

        try {
            $pass    = true;
            $whatsappLogs = CommunicationLog::where('type', ServiceType::WHATSAPP->value)
                                                ->where('status', CommunicationStatusEnum::SCHEDULE->value)
                                                ->whereNotNull("schedule_at")
                                                ->get();

            $i = 1;
            $has_daily_limit = true;
            foreach($whatsappLogs as $whatsappLog) {

                if($whatsappLog->user_id) {

                    $user = User::where("id", $whatsappLog->user_id)->first();
                    $pass = checkCredit($user, strtolower(ServiceType::getValue($whatsappLog->type)));
                    if($user) {

                        $allowed_access = planAccess($user);
                        if(count($allowed_access) > 0) {
                            $allowed_access = (object) planAccess($user);
                            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value);
                            $pass = $has_daily_limit;
                        } else {
                            $pass = false;
                        }

                    } else {
                        $pass = false;
                    }
                }
                if($pass && $whatsappLog->schedule_at && Carbon::now()->greaterThanOrEqualTo($whatsappLog->schedule_at)) {

                    $gateway = WhatsappDevice::find($whatsappLog->gateway_id);
                    
                    if($gateway && $gateway->status == WhatsappDevice::CONNECTED) {
        
                        if($gateway->type == StatusEnum::FALSE->status()) {
                            $whatsappLog->status = CommunicationStatusEnum::PROCESSING->value;
                            $delay = rand($gateway->credentials["min_delay"], $gateway->credentials["max_delay"]) * $i;
                            ProcessWhatsapp::dispatch($whatsappLog, $gateway)->delay(now()->addSeconds($delay));
                        } else {
                            $whatsappLog->status = CommunicationStatusEnum::PROCESSING->value;
                            ProcessWhatsapp::dispatch($whatsappLog, $gateway);
                        }
                        $i++;
                    } else {
                        if($user) {
                            $gateway_name = isset($whatsappLog->meta_data['gateway_name']) ? $whatsappLog->meta_data['gateway_name'] : null;
                            WhatsAppService::updateWhatsappLogAndCredit($whatsappLog, CommunicationStatusEnum::FAIL->value, translate("Choosen Gateway: ").$gateway_name.translate(" could not be found"));
                        } else {

                            $whatsappLog->status = CommunicationStatusEnum::FAIL->value;
                            $whatsappLog->response_message = translate("Choosen Gateway: ").$whatsappLog->meta_data['gateway_name'].translate(" could not be found");
                        }
                        
                        
                    }
                    $whatsappLog->save();
                }
            }
        } catch(Throwable $th) {}
    }

    protected function emailSchedule(): void {

        try {
            $pass      = true;
        $emailLogs = CommunicationLog::where('type', ServiceType::EMAIL->value)                             
                                        ->where('status', CommunicationStatusEnum::SCHEDULE->value)
                                        ->whereNotNull("schedule_at")
                                        ->get();
        $has_daily_limit = true;
        foreach($emailLogs as $emailLog) {

            if($emailLog->user_id) {

                $user = User::where("id", $emailLog->user_id)->first();
                $pass = checkCredit($user, strtolower(ServiceType::getValue($emailLog->type)));
                if($user) {
                 
                	$allowed_access = planAccess($user);
                  	if(count($allowed_access) > 0) {
                    	$allowed_access = (object) planAccess($user);
                        $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value);
                      	$pass = $has_daily_limit;
                    } else {
                    	$pass = false;
                    }
                    
                } else {
                	$pass = false;
                }
            }
            if($pass && $emailLog->schedule_at && Carbon::now()->greaterThanOrEqualTo($emailLog->schedule_at)) {

                $gateway = Gateway::find($emailLog->gateway_id);

                if($gateway) {
                    $emailLog->status = CommunicationStatusEnum::PROCESSING->value;
                    ProcessEmail::dispatch($emailLog, $gateway);
                } else {
                    if($user) {

                        EmailService::updateEmailLogAndCredit($emailLog, CommunicationStatusEnum::FAIL->value, translate("Choosen Gateway: ").$emailLog->meta_data['gateway_name'].translate(" could not be found"));
                    } else {

                        $emailLog->status = CommunicationStatusEnum::FAIL->value;
                        $emailLog->response_message = translate("Choosen Gateway: ").$emailLog->meta_data['gateway_name'].translate(" could not be found");
                    }
                }
                $emailLog->save();
            }
        }
        } catch(Throwable $th) {}
    }

    //Campaigns
    protected function processActiveCampagin(): void {

        try {
            $i         = 1;
            $campaigns = Campaign::with(['communicationLog'])
                                    ->where('status', CampaignStatusEnum::ACTIVE->value)
                                    ->get();
            
            foreach($campaigns as $campaign) {
    
                if(Carbon::now()->greaterThan($campaign->schedule_at)) {
                    
                    foreach($campaign->communicationLog->where("status", CommunicationStatusEnum::PENDING->value) as $log) {
                        
                        $log->status = CommunicationStatusEnum::PROCESSING->value;
                        $log->schedule_at = $campaign->schedule_at;
                        $log->update();
                        $this->processCampaignLogs($log, $i);
                        $i++;
                    }
                    $campaign->status = CampaignStatusEnum::ONGOING->value;
                    $campaign->save();
                } 
            }
        } catch (Throwable $th) {}
    }
    
    protected function processOngoingCampagin(): void {

        try {
            $status = true;
            $campaigns = Campaign::with(['communicationLog'])
                                    ->where('status', CampaignStatusEnum::ONGOING->value)
                                    ->get();
            foreach($campaigns as $campaign) {
                $status = true;
                foreach($campaign->communicationLog as $log) {
                    
                    if($log->status == CommunicationStatusEnum::PENDING->value || $log->status == CommunicationStatusEnum::SCHEDULE->value || $log->status == CommunicationStatusEnum::PROCESSING->value) {
                    
                        $status = false;
                        break;
                    }
                }
                
                if($status) {

                    $campaign->status = CampaignStatusEnum::COMPLETED->value;
                    $campaign->save();
                }
            }
        } catch (Throwable $th) {}
    }

    protected function processCompletedCampagin(): void {

        try {
            $user = null;
            $pass = true;
            $campaigns = Campaign::with(['communicationLog'])
                                    ->where('status', CampaignStatusEnum::COMPLETED->value)
                                    ->get();

            $statuses = [
                CommunicationStatusEnum::DELIVERED->value,
                CommunicationStatusEnum::FAIL->value,
                // CommunicationStatusEnum::CANCEL->value
            ];

            foreach($campaigns as $campaign) {

                $user = null;
                $pass = true;
                $totalcredit = null;
                $has_daily_limit = true;
                if($campaign->user_id) {

                    $user = User::where("id", $campaign->user_id)->first();
                    $pass = checkCredit($user, strtolower(ServiceType::getValue($campaign->type)));
                    if($user) {
                    
                        $allowed_access = planAccess($user);
                        if(count($allowed_access) > 0) {
                            $allowed_access = (object) planAccess($user);
                            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, constant(ServiceType::class . '::' . strtoupper(ServiceType::getValue($campaign->type)))->value);
                            
                        } else {
                            $pass = false;
                        }
                        
                    } else {
                        $pass = false;
                    }
                }
                if($pass && $has_daily_limit && $campaign->repeat_time > 0) {

                    $schedule_at = $this->getNewSchedule($campaign);
                    $logs = $campaign->communicationLog->filter(function ($log) use ($statuses) {
                        return in_array($log->status, $statuses);
                    });
                    $processedContacts = [];

                    foreach($logs as $log) {
                        
                        if (site_settings('filter_duplicate_contact') == StatusEnum::TRUE->status()) {

                            if (in_array($log->contact_id, $processedContacts)) {
                                continue;
                            }
                        }
                        
                        $isUnsubscribed = CampaignUnsubscribe::where('contact_uid', @$log?->contact?->uid)
                            ->where('campaign_id', $log->campaign_id)
                            ->where('channel', ServiceType::EMAIL->value) 
                            ->exists();
                        if ($isUnsubscribed) {
                            
                            continue;
                        }
                        $newLog = $log->replicate();
                        $newLog->schedule_at = $schedule_at;
                        unset($newLog->status);
                        $newLog->status = (string)CommunicationStatusEnum::SCHEDULE->value;
                        $newLog->save();
                        $processedContacts[] = $log->contact_id;
                        if($user) {
                            if($newLog->type == ServiceType::SMS->value) {

                                $messages    = str_split($newLog->message["message_body"], $newLog->meta_data["sms_type"] == "unicode" ? site_settings("sms_word_unicode_count") : site_settings("sms_word_count"));
                                $totalcredit = count($messages);
                            } elseif($newLog->type == ServiceType::WHATSAPP->value) {

                                $messages    = str_split($newLog->message["message_body"], site_settings("whatsapp_word_count"));
                                $totalcredit = count($messages);
                            } else {
                                $totalcredit = 1;
                            }
                            $this->customerService->deductCreditLog($user, (int)$totalcredit, $newLog->type);
                            
                        }
                    }
                    $campaign->schedule_at = $schedule_at;
                    $campaign->status = CampaignStatusEnum::ACTIVE->value;
                    $campaign->save();
                    
                } elseif(!$has_daily_limit) {

                } else {

                    $campaign->status = CampaignStatusEnum::COMPLETED->value;
                    $campaign->save();
                }
            }
        } catch(Throwable $th) {}
    }

    private function getNewSchedule($campaign) {

        try {
            $schedule_at = Carbon::parse($campaign->schedule_at); 
        
            $repeat_time = $campaign->repeat_time; 
            if ($campaign->repeat_format == CampaignRepeatEnum::DAY->value) {

                $schedule_at->addDays($repeat_time);
            } elseif ($campaign->repeat_format == CampaignRepeatEnum::WEEK->value) {

                $schedule_at->addWeeks($repeat_time);
            } elseif ($campaign->repeat_format == CampaignRepeatEnum::MONTH->value) {

                $schedule_at->addMonths($repeat_time);
            } elseif ($campaign->repeat_format == CampaignRepeatEnum::YEAR->value) {

                $schedule_at->addYears($repeat_time);
            }
        
            return $schedule_at->toDateTimeString(); 
        } catch (Throwable $th) {}
    }

    private function processCampaignLogs($log, $i = null) {
        
        try {
            if($log->type == ServiceType::SMS->value) {
            
                if($log->android_gateway_sim_id) {
    
                    $log->status = CommunicationStatusEnum::PENDING->value;
                    $log->update();
                } else {
                    
                    $gateway = $log->gateway_id ? Gateway::where("id",$log->gateway_id)->first() : null;
                    if($gateway) {
    
                        ProcessSms::dispatch($log, $gateway);
                    } else {
    
                        return false;
                    }
                }
            } elseif($log->type == ServiceType::EMAIL->value) {
    
                $gateway = $log->gateway_id ? Gateway::where("id",$log->gateway_id)->first() : null;
                if($gateway) {
    
                    ProcessEmail::dispatch($log, $gateway);
                } else {
    
                    return false;
                }
    
            } elseif($log->type == ServiceType::WHATSAPP->value) {
    
                $gateway = $log->gateway_id ? WhatsappDevice::where("id", $log->gateway_id)->first() : null;
                
                if($gateway) {
                    if($gateway->type == StatusEnum::FALSE->status()) {
                       
                        $delay = rand($gateway->credentials["min_delay"], $gateway->credentials["max_delay"]) * $i;
                        ProcessWhatsapp::dispatch($log, $gateway)->delay(now()->addSeconds($delay));
                    } else {
        
                        ProcessWhatsapp::dispatch($log, $gateway);
                    }
                } else {
    
                    false;
                }
            } else {
    
                return false;
            }
            return true;
        } catch (Throwable $th) {}
    }

    //Android gateway update
    protected function updateAndroidGateway() {

        try {
            $logs = CommunicationLog::where('type', ServiceType::SMS->value)
                ->whereNotNull('campaign_id')
                ->where(function ($query) {
                    $query->where('status', '!=', CommunicationStatusEnum::DELIVERED)
                        ->orWhere('status', '!=', CommunicationStatusEnum::FAIL);
                })
                ->whereNull('response_message')
                ->whereNull('gateway_id')
                ->whereNotNull("android_gateway_sim_id")
                ->get();
        
            foreach($logs as $log) {

                if($log->user_id) {
                
                    $user = User::where("id", $log->user_id)->first();
                    if($user) {

                        $plan_access = planAccess($user);
                        if(count($plan_access) > 0) {

                            $plan_access                 = (object) planAccess($user);
                            $sim                         = $plan_access->type == StatusEnum::FALSE->status() ? $this->androidUserGatewayUpdate($log) : $this->androidAdminGatewayUpdate($log);
                            $meta_data                   = $log->meta_data;
                            $meta_data["gateway"]        = $sim->androidGateway->name;
                            $meta_data["gateway_name"]   = $sim->sim_number;
                            $log->android_gateway_sim_id = $sim->id;
                            $log->meta_data              = $meta_data;   
                            $log->save();
                        }
                    }
                } else {

                    $sim       = $this->androidAdminGatewayUpdate($log);
                    $meta_data = $log->meta_data;
                    if($sim->androidGateway) {
                    
                    $meta_data["gateway"]        = $sim->androidGateway->name;
                    $meta_data["gateway_name"]   = $sim->sim_number;
                    $log->android_gateway_sim_id = $sim->id;
                    $log->meta_data              = $meta_data;   
                    $log->save();
                    }
                }
            }
        } catch(Throwable $th) {}
    }

    private function androidUserGatewayUpdate($log) {

        try {
            $sim = AndroidApiSimInfo::where("id", $log->android_gateway_sim_id)->first();
      	
            if(!$sim || $sim->status == AndroidApiSimEnum::INACTIVE->value) {
    
                $gateway = AndroidApi::where("user_id", $log->user_id)->inRandomOrder()->first();
                $new_sim = AndroidApiSimInfo::where("android_gateway_id", $gateway->id)->where("status", AndroidApiSimEnum::ACTIVE)->first();
                if($new_sim) {
                    
                    $sim = $new_sim;
                }
            }
            return $sim;
        } catch (Throwable $th) {}
    }

    private function androidAdminGatewayUpdate($log) {

        try {
            $sim = AndroidApiSimInfo::where("id", $log->android_gateway_sim_id)->first();
        
            if(!$sim || $sim->status == AndroidApiSimEnum::INACTIVE->value) {

                $gateway = AndroidApi::whereNull("user_id")->inRandomOrder()->first();
                $new_sim = AndroidApiSimInfo::where("android_gateway_id", $gateway->id)->where("status", AndroidApiSimEnum::ACTIVE)->first();
                if($new_sim) {

                    $sim = $new_sim;
                }
            }
        
            return $sim;
        } catch(Throwable $th) {}
    }

    //Plan Expiration Check
    protected function checkPlanExpiration() {
        try {
            $subscriptions = Subscription::where('status', SubscriptionStatus::RUNNING->value)->orWhere('status', SubscriptionStatus::RENEWED->value)->get();
            foreach($subscriptions as $subscription) {

                $expiredTime = $subscription->expired_date;
                $now = Carbon::now()->toDateTimeString();
                if($now > $expiredTime) {

                    $subscription->status = SubscriptionStatus::EXPIRED->value;
                    $subscription->save();
                }
            }
        } catch(Throwable $th) {}
    }
}
