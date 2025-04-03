<?php

namespace App\Service\Admin\Dispatch;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SMSlog;
use App\Models\Contact;
use App\Models\Gateway;
use App\Jobs\ProcessSms;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Utility\SendSMS;
use App\Models\GeneralSetting;
use App\Models\CampaignContact;
use App\Models\AndroidApiSimInfo;
use App\Http\Requests\StoreSMSRequest;
use App\Http\Utility\SendMail;
use App\Imports\ContactImport;
use App\Models\AndroidApi;
use App\Models\CommunicationLog;
use App\Models\ContactGroup;
use App\Models\Group;
use App\Models\Template;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Core\FileService;
use App\Service\Admin\Gateway\SmsGatewayService;
use App\Services\System\Contact\ContactService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SmsService {

    public CustomerService $customerService;
    public $fileService;
    public $contactService;
    public $smsGatewayService;

    /**
     *
     * @param CustomerService $customerService
     * 
     * @param FileService $customerService
     */
    public function __construct (CustomerService $customerService) {

        $this->customerService = $customerService;
        $this->fileService = new FileService;
        $this->contactService = new ContactService;
        $this->smsGatewayService = new SmsGatewayService;
    }

    /**
     * 
     * @return CommunicationLog
     */
    public function logs($user_id = null, $campaign_id = null) {
        
        $query = CommunicationLog::search(['user:name', 'meta_data[contact]'])
            ->latest()
            ->date()
            ->filter(['status'])
            ->with('user')
            ->routefilter();
        if ($user_id) {

            $query->where('user_id', $user_id);
        }
        if ($campaign_id) {
            
            $query->where('campaign_id', $campaign_id);
        }
        return $query->paginate(paginateNumber(site_settings('paginate_number')))
            ->appends(request()->all());
    }

    /**
     * 
     * @param $status
     * 
     * @return AndroidApi
     */
    public function androidGateways($status, $type = null) {

        $gateways = [];
       
        if(!is_null($type)) {
            
            $gateways = $type == StatusEnum::FALSE->status() ? 

                                AndroidApi::where("user_id", auth()->user()->id)
                                            ->with('simInfo')
                                            ->where("status", $status)
                                            ->latest()
                                            ->get() :
                                AndroidApi::whereNull("user_id")
                                        ->with('simInfo')
                                        ->where("status", $status)
                                        ->latest()
                                        ->get();
            
        } else {

            $gateways = AndroidApi::with('simInfo')
                            ->whereNull("user_id")
                            ->where("status", $status)
                            ->latest()
                            ->get();
        }
        return $gateways;
    }

    /**
     * 
     * @return Gateway
     */
    public function smsGateway($type = null, $user_id = null) {
        
        $query = Gateway::where('status', StatusEnum::TRUE->status())
            ->whereNotNull('sms_gateways')
            ->get(['id', 'type', 'name', 'user_id']);

     

        if(is_null($type)){
            $query = $query->whereNull("user_id");
        }
        $query = $type == StatusEnum::FALSE->status() ? $query->where("user_id", $user_id) : $query->whereNull("user_id");
       
        return $query->mapWithKeys(function ($item) {
            
            return [
                $item->type => [
                    $item->id => $item->name
                ]
            ];
        });
    }

    public function getGroupWhereColumn($column_name, $user_id = null) {

        return ContactGroup::where("user_id", $user_id)->whereHas('contacts', function ($query) use ($column_name) {
            $query->whereNotNull($column_name);
        })->get();
    }

    /**
     * 
     * @param $status
     * 
     * @param $type
     * 
     * @return Template
     */

    public function getTemplateWithStatusType($status, $type, $user_id = null) {

        return Template::where([
            'user_id'  => $user_id,
            'status' => $status,
            'type'   => $type,
        ])->latest()->get();
    }

    /**
     * @param array $request
     * 
     * @return array $status, $message
     *  
     * */ 
    public function statusUpdate(array $data, $log): array {
        
        $status  = 'success';
        $message = translate("SMS request has been registered successfully");
        $user_id = null;
        if($log->user_id) {
            $user_id = $log->user_id;
        }
        if($data['method'] == StatusEnum::FALSE->status()) {

            $log->gateway_id = null;
        } else {
            $log->android_gateway_sim_id = null;
        }
        $log->save();
        if($data['status'] == CommunicationStatusEnum::PENDING->value) {

            $meta_data[] = $log->meta_data;
            list($status, $message, $meta_data, $gateway) = $this->smsGatewayService->assignGateway($data['method'], array_key_exists('gateway_id', $data) ? $data['gateway_id'] :null, $meta_data, $log->meta_data['sms_type']);
            if(!$gateway) {
                return [
                    "error",
                    translate("Cannot Access SMS Gateway")
                ];
            }
            $item_data = $data;
            $item_data['gateway_id'] = $gateway->id;
            if($item_data['method'] == StatusEnum::FALSE->status()) {
                
                $item_data['android_gateway_sim_id'] = $gateway->id;
                unset($item_data['gateway_id']);
            } else {
                $item_data['gateway_id'] = $gateway->id;
            }
            $this->prepData($item_data, $meta_data, $gateway, $user_id, true);
           
        } elseif($data['status'] == CommunicationStatusEnum::DELIVERED->value) {

            $log->status = $data["status"];
            $meta_data = $log->meta_data;
            $meta_data["delivered_at"] = Carbon::now()->toDayDateTimeString();
            $log->meta_data = $meta_data;
            $log->update();
        } else {
            $log->status = $data["status"];
            $log->update();
        }
        
        return [
            $status,
            $message
        ];
    }

    /**
     * Bulk action update/delete
     *
     * @param Request $request
     * @param array $modelData
     * @return array
     */
    public function bulkAction(Request $request, $type, array $modelData) {
        
        
        $data = $request->toArray();
        unset($data['_token'],$data['ids']);
        $status  = 'success';
        $message = translate("Successfully performed bulk action, no campaign log were effected by this bulk action");
        $model   = $modelData['model'];
        $ids     = $request->input('ids', []);
        
        if (empty($ids)) {

            return ['error', translate("No items selected")];
        }
        
        $type = $request->input('type');
        
        try {
            DB::beginTransaction();

            if ($type === 'delete') {

                foreach ($ids as $id) {

                    $item = $model::find($id);
                    $item->delete();
                }
                $message = translate("Successfully deleted selected items");
            } elseif ($type === 'status') {
                
                foreach ($ids as $id) {
                    
                    $statusValue = $request->input('status');
                    $item = $model::find($id);

                    if(!$item->campaign_id) {

                        if($request->has('method')) {
                            list($status, $message, $meta_data, $gateway) = $this->smsGatewayService->assignGateway($data['method'], array_key_exists('gateway_id', $data) ? $data['gateway_id'] : null, $item->meta_data, array_key_exists('sms_type', $item->meta_data) ? $item->meta_data['sms_type'] : 'plain');
                            
                            $item_data = $data;
                            $meta_data["delivered_at"] = "N\A";
                          
                            if($gateway && $item_data['method'] == StatusEnum::FALSE->status() && array_key_exists('gateway_id', $item_data)) {
                                $item->gateway_id = null;
                                $item_data['android_gateway_sim_id'] = $gateway->id;
                                unset($item_data['gateway_id']);
                            } else {
                                $item_data['gateway_id'] = $gateway ? $gateway->id : $item->gateway_id;
                            }
                            
                            $item_data['id'] = $id;
                            $item_data['schedule_at'] = null;
                            $this->prepData($item_data, $meta_data, $gateway);
                            if($gateway) {
                                $this->send($gateway, count($ids), $item);
                            }
                        }
                        $item->status = $statusValue;
                        if($item->status == CommunicationStatusEnum::DELIVERED->value) {
                            $meta_data = $item->meta_data;
                            $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
                            $item->meta_data = $meta_data;
                        }
                        $item->update();
                    }
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
     
            DB::rollBack();
            return ['error', translate("Server Error: ") . $exception->getMessage()];
        }

        return [$status, $message];
    }

    private function checkDailyLimit($user_id, &$meta_data, &$status, &$message, &$pass)
    {
        $user = User::where("id", $user_id)->first();
        $allowed_access = (object) planAccess($user);
        $status  = 'success';
        $message = translate("You have not yet exceeded the daily credit limit for SMS messages");
        $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value, count($meta_data));
       
        if (!$has_daily_limit) {
           
            $pass    = $has_daily_limit;
            $status  = 'error';
            $message = translate("Contact quantity exceeds allowed daily limits");
        }
    }

    /**
     * @param string $type
     * 
     * @param array $data
     * 
     * @param string $contact_type
     * 
     * @return array $status, $message
     *  
     * */ 
    public function store(string $type, array $data, string $contact_type, $user_id = null): array {
        
        $status  = 'success';   
        $message = translate("SMS request has been registered successfully");
        $meta_data = [];
        $pass      = true;
        
        switch ($contact_type) {

            case 'file':

                $contacts  = $data['contacts'];
                [$status, $message, $meta_data] = $this->fileService->readCsv($contacts);
                if(count($meta_data) <= 0) {
                    return [
                        $status,
                        $message
                    ];
                }
                if ($user_id) {

                    $this->checkDailyLimit($user_id, $meta_data, $status, $message, $pass);
                }
                if (site_settings('filter_duplicate_contact') == StatusEnum::TRUE->status()) {
                    $meta_data = filterDuplicateContacts($meta_data);
                }
                break;

            case 'array':
                
                $contacts    = $data['contacts'];
                $meta_name   = array_key_exists('attribute_name', $data) ? $data['attribute_name'] : null;
                $logic       = array_key_exists('logic', $data) ? $data['logic'] : null;
                $logic_range = array_key_exists('logic_range', $data) ? $data['logic_range'] : null;
                $group_logic = array_key_exists('group_logic', $data) ? $data['group_logic'] : null;
                $meta_data   = $this->contactService->retrieveContacts($type, $contacts, $group_logic, $meta_name, $logic, $logic_range, $user_id);
                if ($user_id) {

                    $this->checkDailyLimit($user_id, $meta_data, $status, $message, $pass);
                }
                if (site_settings('filter_duplicate_contact') == StatusEnum::TRUE->status()) {
                    $meta_data = filterDuplicateContacts($meta_data);
                }
                break;

            case 'text':

                $contact = $data['contacts'];
                $meta_data = [
                    [
                        'contact' =>  $contact
                    ]
                ];
                if ($user_id) {

                    $this->checkDailyLimit($user_id, $meta_data, $status, $message, $pass);
                }
                if (site_settings('filter_duplicate_contact') == StatusEnum::TRUE->status()) {
                    $meta_data = filterDuplicateContacts($meta_data);
                }
                break;

            default:
                $status = 'error';
                $message = "Unknown contact type.";
                break;
        }
        if(!$pass) {
            return [
                $status,
                $message
            ];
        }
        list($status, $message, $meta_data, $gateway) = $this->smsGatewayService->assignGateway($data['method'], $data['gateway_id'], $meta_data, $data['sms_type'], null, $user_id);
        
        if($gateway) {

            if($data['method'] == StatusEnum::FALSE->status()) {

                $data['android_gateway_sim_id'] = $gateway ? $gateway->id : null;
                unset($data['gateway_id']);
                $gateway = $data['android_gateway_sim_id'] ? null : $gateway;
            } else {
                $data['gateway_id'] = $gateway->id;
            }
           
            list($status, $message) = $this->prepData($data, $meta_data, $gateway, $user_id);
            
        }
       
        return [
            $status,
            $message
        ];
    }

    public function getLogById($id) {

        return CommunicationLog::where('id', $id)->first();
    }

    public function saveLog($data) {
        
        $id = null;
        if(array_key_exists('id', $data)) {

            $id = (int)$data['id'];
            unset($data['id']);
        }
        $data = $this->prepForLog($this->dataFields(), $data);
        if(array_key_exists('schedule_at', $data) && $data['schedule_at']) {

            $data['status'] = (string)CommunicationStatusEnum::SCHEDULE->value;
        } else {

            $data['status'] = (string)CommunicationStatusEnum::PENDING->value;
        }
        return CommunicationLog::updateOrCreate([
            'id' => $id
        ], $data);
    }
    
    public function prepForLog($fieldsToKeep, $data) {

        foreach($data as $key => $value) {
            if (!in_array($key, $fieldsToKeep)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    public function dataFields() {

        return [
            'user_id',
            'contact_id',
            'type',
            'gateway_id',
            'campaign_id',
            'message',
            'meta_data',
            'android_gateway_sim_id',
            'schedule_at'
        ];
    }

    public function prepData($data, $meta_data, $gateway, $user_id = null, $update_status = false) {
        
       
        $status  = 'success';
        $message = translate("SMS request has been registered successfully");
        $total_credits = null;
        unset($data['contacts'], $data['method']);
        $default = [
            "type"    => ServiceType::SMS->value,
            "user_id" => $user_id
        ];
        if($user_id) {
            
            $user = User::where("id", $user_id)->first();
            $allowed_access = (object) planAccess($user);
            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value);
            
            if($has_daily_limit || $update_status) {
               
                $remaining_sms_credits = $user->sms_credit;
                $word_length   = $data["sms_type"] == "unicode" ? site_settings("sms_word_unicode_count") : site_settings("sms_word_count");
                $total_message = count(str_split($data["message"]["message_body"],$word_length));
                $total_contact = count($meta_data);
                $total_credits = $total_contact * $total_message;
                if (($total_credits > $remaining_sms_credits && $user->sms_credit != -1) && !$update_status) {
    
                    $status  = 'error';
                    $message = translate("You do not have sufficient credits for sending a message");
                } else {
                    $this->customerService->deductCreditLog($user, (int)$total_credits, ServiceType::SMS->value);
                    unset($data['sms_type']);
                    if($this->smsGatewayService->hasNestedArray($meta_data)) {
                        $content_message = $data['message'];
                        foreach($meta_data as $key => $value) {
                            
                            $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null ;
                            $data['message'] = $this->getFinalContent($content_message, $value);
                            $value["total_credits"] = $total_credits;
                            unset($value['id']);
                            $data['meta_data'] = $value;
                            $data = array_merge($data, $default);
                            $log = $this->saveLog($data);
                            if($log->android_gateway_sim_id && $log->schedule_at) {
            
                                $log->status = CommunicationStatusEnum::SCHEDULE->value;
                                $log->save();
                            } 
                            if($gateway && !$log->campaign_id) {
                                
                                $this->send($gateway, count($meta_data), $log);
                            }
                        }
                    } else {
                       
                        $data['meta_data'] = $meta_data;
                        $data = array_merge($data, $default);
                        $log = $this->saveLog($data);
                        
                        if($log->android_gateway_sim_id && $log->schedule_at) {
            
                            $log->status = CommunicationStatusEnum::SCHEDULE->value;
                            $log->save();
                        }
                        if($gateway && !$log->campaign_id) {
                            
                            $this->send($gateway, 1, $log);
                        }
                    }
                }
            } else {
                
                $status  = 'error';
                $message = translate("You have exceeded the daily credit limit for SMS messages");
            }
           
        } else {
            unset($data['sms_type']);
            if($this->smsGatewayService->hasNestedArray($meta_data)) {
                $content_message = $data['message'];
                foreach (array_chunk($meta_data, 100) as $chunk) {
                    
                    foreach ($chunk as $key => $value) {
                        
                        $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null;
                        $data['message'] = $this->getFinalContent($content_message, $value);
                        $value["total_credits"] = $total_credits;
                        unset($value['id']);
                        $data['meta_data'] = $value;
                        $data = array_merge($data, $default);
                        $log = $this->saveLog($data);
                        if ($log->android_gateway_sim_id && $log->schedule_at) {
                            $log->status = CommunicationStatusEnum::SCHEDULE->value;
                            $log->save();
                        } 
                        if ($gateway && !$log->campaign_id) {
                            $this->send($gateway, count($meta_data), $log);
                        }
                    }
                }
            } else {
               
                $data['meta_data'] = $meta_data;
                $data = array_merge($data, $default);
                $log = $this->saveLog($data);
                
                if($log->android_gateway_sim_id && $log->schedule_at) {
    
                    $log->status = CommunicationStatusEnum::SCHEDULE->value;
                    $log->save();
                }
                if($gateway && !$log->campaign_id) {
                    
                    $this->send($gateway, 1, $log);
                }
            }
        }
        return [$status, $message];
    }

    public function send($gateway, $total_contact, $log) {
        
        if ($log->schedule_at) {
            $scheduledTime = Carbon::parse($log->schedule_at);
            
            if ($scheduledTime->isFuture()) {
             
                $log->gateway_id = $gateway->id;
                $log->status = CommunicationStatusEnum::SCHEDULE->value;
                $log->save();
                return;
            }
            
        } elseif(is_null($log->android_gateway_sim_id)) {
            
            if ($total_contact > 1) {
                
                ProcessSms::dispatch($log, $gateway);
                
            } else {
                
                $api_method = transformToCamelCase($gateway->type);
                
                SendSMS::$api_method(
                    $log->meta_data['contact'],
                    $log->meta_data['sms_type'],
                    $log->message['message_body'],
                    (object)$gateway->sms_gateways,
                    $log->id
                );
            }
        }
    }
    
   /**
     * @param mixed $value
     * 
     * @param array $message
     * 
     * @return string
     */
    public function getFinalContent(array $message, mixed $value): array {

        $message['message_body'] = textSpinner($message['message_body']);
        foreach ($value as $key => $val) {
            $message = str_replace('{{' . $key . '}}', $val, $message);
        }
        
        return $message;
    }

    /**
     * @param SMSlog $log
     * @param $status
     * @param $errorMessage
     * @return void
     */
    public static function updateSMSLogAndCredit(CommunicationLog $log, $status, $errorMessage = null): void {
        
        $log->status           = $status;
        $log->response_message = !is_null($errorMessage) ? $errorMessage : null;
        $log->save();
        $user = User::find($log->user_id);

        if ($user && $status == CommunicationStatusEnum::FAIL->value) {

            $messages    = str_split($log->message["message_body"], $log->meta_data["sms_type"] == "unicode" ? site_settings("sms_word_unicode_count") : site_settings("sms_word_count"));
            $totalcredit = count($messages);
            CustomerService::addedCreditLog($user, $totalcredit, ServiceType::SMS->value);
        }
    }





    /**
     * @param mixed $smsLog 
     * 
     * @param int|null $diffInSeconds 
     * 
     * @return void
     * 
     */
    public function sendSmsByOwnGateway($smsLog, $diffInSeconds = null, $contactCount = null):void {
        
        if (is_null($smsLog->android_gateway_sim_id) && !is_null($smsLog->api_gateway_id)) {
            
            $smsGateway = Gateway::where('id', $smsLog->api_gateway_id)->first();
            $creds      = $smsGateway->sms_gateways;

            if ($smsGateway && $contactCount > 1) {
                 
                ProcessSms::dispatch($smsLog, (array)$creds, $smsGateway)->delay(now()->addSeconds($diffInSeconds));
            } else {

                try {
                        
                    $smsLog->api_gateway_id         = $smsGateway->id;
                    $smsLog->android_gateway_sim_id = null;
                    $smsType                        = $smsLog->sms_type == 1 ? 'plain' : 'unicode';
                    $gateways = [
                        "101NEXMO"         => 'nexmo',
                        "102TWILIO"        => 'twilio',
                        "103MESSAGE_BIRD"  => 'messageBird',
                        "104TEXT_MAGIC"    => 'textMagic',
                        "105CLICKA_TELL"   => 'clickaTell',
                        "106INFOBIP"       => 'infoBip',
                        "107SMS_BROADCAST" => 'smsBroadcast',
                        "108MIM_SMS"       => 'mimSMS',
                        "109AJURA_SMS"     => 'ajuraSMS',
                        "110MSG91"         => 'msg91'
                    ];
                    
                    if (isset($gateways[$smsGateway->type])) {

                        $gateway = $gateways[$smsGateway->type];
                        SendSMS::$gateway($smsLog->to, $smsType, $smsLog->message, (object)$creds, $smsLog->id);
                    }
                  
                } catch (\Exception $exception) { }
            }
        }
	}

    /**
     * Searches SMS logs based on provided criteria.
     *
     * @param string|null $search The search query for user name or phone number.
     * @param string|null $searchDate The date range in format 'mm/dd/yyyy - mm/dd/yyyy'.
     * @return Builder Query builder for SMS logs.
     */
    public function searchSmsLog($search, $searchDate): Builder {

        $smsLogs = SMSlog::query();

        if (!empty($search)) {

            $smsLogs->whereHas('user',function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")->orWhere('to', 'like', "%$search%");
            });
        }

        if (!empty(request()->input('status'))) {

            $status = match (request()->input('status')) {

                'pending'    => [1],
                'schedule'   => [2],
                'fail'       => [3],
                'delivered'  => [4],
                'processing' => [5],
                default      => [1,2,3,4,5],
            };
            $smsLogs->whereIn('status',$status);
        }

        if (!empty($searchDate)) {

            $dateRange = explode('-', $searchDate);
            $firstDate = Carbon::createFromFormat('m/d/Y', trim($dateRange[0]))->startOfDay();
            $lastDate  = isset($dateRange[1]) ? Carbon::createFromFormat('m/d/Y', trim($dateRange[1]))->endOfDay() : null;
            if ($firstDate) {

                $smsLogs->whereDate('created_at', '>=', $firstDate);
            }
            if ($lastDate) {

                $smsLogs->whereDate('created_at', '<=', $lastDate);
            }
        }

        return $smsLogs;
    }

    /**
     * Updates status of SMS logs and processes them accordingly.
     *
     * @param int $status The new status for the SMS logs.
     * @param array $smsLogIds The IDs of the SMS logs to be updated.
     * @param GeneralSetting $general The general settings instance.
     * @param int|null $sim_id The ID of the SMS for which the status is being updated (if applicable).
     * @return void
     */
	public function smsLogStatusUpdate(int $status, array $smsLogIds, GeneralSetting $general, ?int $sim_id):void {
        
        $general = GeneralSetting::first();
       
		foreach (array_reverse($smsLogIds) as $smsLogId) {

			$smslog = SMSlog::find($smsLogId);

			if (!$smslog) { 
                
                continue; 
            }

            $user       = User::find($smslog->user_id);
            $wordLength = $smslog->sms_type == "plain" ? $general->sms_word_text_count : $general->sms_word_unicode_count;
            
            if ($status == SMSlog::PENDING && $user) {
                
                $messages    = str_split($smslog->message,$wordLength);
                $totalCredit = count($messages);
                
                if ($user->credit >= $totalCredit) {

                    $smslog->status = $status;
                    if($smslog->api_gateway_id) {

                        ProcessSms::dispatch($smslog, (array)$smslog->smsGateway()->first()->sms_gateways, $smslog->smsGateway()->first());
                    }
                    $this->customerService->deductCreditLog($user, $totalCredit, ServiceType::SMS->value);

                } else {
                    $mailCode = [
                        'type'           => "sms",
                        'name' => site_settings("site_name"),
                        'time' => Carbon::now(),
                    ];
                    SendMail::MailNotification($user,'INSUFFICIENT_CREDIT',$mailCode);
                }
            } else {

                $smslog->status = $status;
                if ($sim_id) {

                    $sim_number                     = AndroidApiSimInfo::where("id", $sim_id)->value("sim_number");
                    $smslog->api_gateway_id         = null;
                    $smslog->android_gateway_sim_id = $sim_id;
                    $smslog->sim_number             = $sim_number;
                    $smslog->save();
                } elseif(!$smslog->api_gateway_id && !$sim_id) {
                    
                    $smslog->android_gateway_sim_id = null;
                    $smslog->save();
                }
                if($smslog->api_gateway_id) {
                    ProcessSms::dispatch($smslog, (array)$smslog->smsGateway()->first()->sms_gateways, $smslog->smsGateway()->first());
                } 
            } //send one 
			$smslog->update();
		}
	}

    /**
     * @param Request $request
     * @param array $allContactNumber
     * @return void
     */
    public function processNumber(Request $request, array &$allContactNumber): void {
        
        if ($request->has('number')) {
            $contactNumber       = preg_replace('/[ ,]+/', ',', trim($request->input('number')));
            $allContactNumber[]  = explode(",",$contactNumber);
        }
    }

    /**
     * @param Request $request
     * @param array $allContactNumber
     * @param array $numberGroupName
     * @param null $userId
     * @return void
     */
    public function processGroupId(Request $request, array &$allContactNumber, array &$numberGroupName, $userId = null): void {

        if ($request->has('group_id')) {
           
            $contact = Contact::query();
            $contact->whereIn('group_id', $request->input('group_id'));

            if ($request->input("group_logic")) {

                $logic         = $request->input("logic");
                $attributeName = $request->input("attribute_name");
            
                if (strpos($attributeName, "::") !== false) {

                    $attributeParts = explode("::", $attributeName);
                    $attributeType  = $attributeParts[1];
                    
                    if ($attributeType == GeneralSetting::DATE) {

                        $startDate = Carbon::parse($logic);
            
                        if ($request->has('logic_range')) {

                            $endDate = Carbon::parse($request->input('logic_range'));
                            $contact = $contact->get()->filter(function ($contact) use ($startDate, $endDate, $attributeParts) {

                                $attr = Carbon::parse($contact->attributes->{$attributeParts[0]}->value);
                                return $attr->between($startDate, $endDate);
                            });
                        } else {

                            $contact = $contact->get()->filter(function ($contact) use ($startDate, $attributeParts) {

                                $attr = Carbon::parse($contact->attributes->{$attributeParts[0]}->value);
                                return $attr->isSameDay($startDate);
                            });
                        }
                    } elseif ($attributeType == GeneralSetting::BOOLEAN) {

                        $logicValue = filter_var($logic, FILTER_VALIDATE_BOOLEAN);
                        $contact    = $contact->get()->filter(function ($contact) use ($attributeParts, $logicValue) {

                            $attrValue = filter_var($contact->attributes->{$attributeParts[0]}->value, FILTER_VALIDATE_BOOLEAN);
                            return $attrValue === $logicValue;
                        });

                    } elseif ($attributeType == GeneralSetting::NUMBER) { 

                        $numericLogic = filter_var($logic, FILTER_VALIDATE_FLOAT);
                    
                        if ($request->has('logic_range')) {

                            $numericRange = filter_var($request->input('logic_range'), FILTER_VALIDATE_FLOAT);
                            $contact      = $contact->get()->filter(function ($contact) use ($attributeParts, $numericLogic, $numericRange) {

                                $attrValue = filter_var($contact->attributes->{$attributeParts[0]}->value, FILTER_VALIDATE_FLOAT);
                                return $attrValue >= $numericLogic && $attrValue <= $numericRange;
                            });
                        } else {

                            $contact = $contact->get()->filter(function ($contact) use ($attributeParts, $numericLogic) {

                                $attrValue = filter_var($contact->attributes->{$attributeParts[0]}->value, FILTER_VALIDATE_FLOAT);
                                return $attrValue == $numericLogic;
                            });
                        }
                    } elseif ($attributeType == GeneralSetting::TEXT) { 

                        $textLogic = $request->input('logic');
                        $contact   = $contact->get()->filter(function ($contact) use ($attributeParts, $textLogic) {

                            $attrValue = $contact->attributes->{$attributeParts[0]}->value;
                            return stripos($attrValue, $textLogic) !== false;
                        });
                    }
                } else {
                    $contact->where($attributeName, 'like', "%$logic%");
                }
            }
            
            if (!is_null($userId)) {

                $contact->where('user_id', $userId);
            } else {

                $contact->whereNull('user_id');
            }
            if ($request->has("channel")) {

                $allContactNumber[] = $contact->pluck("$request->channel".'_contact')->toArray();
                $numberGroupName    = $contact->pluck('first_name', "$request->channel".'_contact')->toArray();
                
            }
        }
    }

    /**
     * @param Request $request
     * @param array $allContactNumber
     * @param array $numberGroupName
     * @return void
     */
    public function processFile(Request $request, array &$allContactNumber, array &$numberGroupName): void {
       
        if ($request->has('file')) {

            $service   = new FileProcessService();
            $extension = strtolower($request->file('file')->getClientOriginalExtension());
            
            if ($extension == "csv") {

                $response           = $service->processCsv($request->file('file'));
                $allContactNumber[] = array_keys($response);
                $numberGroupName    = $numberGroupName + $response;
            }

            if ($extension == "xlsx") {
                
                $response =  $service->processExel($request->file('file'));
                $allContactNumber[] = array_keys($response);
                $numberGroupName = $numberGroupName + $response;
            }
        }
    }

    /**
     * @param $allContactNumber
     * @return array
     */
    public function flattenAndUnique($allContactNumber): array {

        $contactNewArray = [];
        foreach ($allContactNumber as $childArray) {

            foreach ($childArray as $value) {

                $contactNewArray[] = $value;
            }
        }
        $filtered = Arr::where($contactNewArray, function (string|int $value, int $key) {

            return $value !== "" && filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        });
        return array_unique($filtered);
    }

    

    /**
     * @param string $contact
     * @param StoreSMSRequest $request
     * @param array $numberGroupName
     * @param int|null $apiGatewayId
     * @param int|null $userId
     * @return array
     */
    public function prepParams(string $contact, StoreSMSRequest $request, array $numberGroupName, ?int $apiGatewayId, ?int $simId, ?int $userId = null): array {   
       
        $generalSetting = GeneralSetting::first();
        $value          = array_key_exists($contact, $numberGroupName) ? $numberGroupName[$contact] ?? translate("User") : $contact;
        $setTimeInDelay = $request->input('schedule') == 2 ? $request->input('schedule_date') : Carbon::now();
        $wordLength     = $request->input('smsType') == "plain" ? $generalSetting->sms_word_text_count : $generalSetting->sms_word_unicode_count;
        $finalContent   = $this->getFinalContent($value, $numberGroupName, $request->input('message'));
        $sim_number     = null;
        if($simId) {

            $sim_number = AndroidApiSimInfo::where("id", $simId)->value("sim_number");
        }
        return  [
            'to'                     => $contact,
            'word_length'            => $wordLength,
            'user_id'                => $userId,
            'sms_type'               => $request->input('sms_type') == "plain" ? 1 : 2,
            'initiated_time'         => $setTimeInDelay,
            'message'                => $finalContent,
            'status'                 => $request->input('schedule') == 2 ? 2 : SMSlog::PENDING,
            'schedule_status'        => $request->input('schedule'),
            'api_gateway_id'         => $apiGatewayId,
            'android_gateway_sim_id' => $simId,
            'sim_number'             => $sim_number ? $sim_number : null
        ];
    }

    /**
     * @param array $params
     * @return SMSlog
     */
    public function saveSMSLog(array $params): SMSlog {

        return SMSlog::create([
            'to'                     => $params['to'],
            'word_length'            => $params['word_length'],
            'user_id'                => $params['user_id'],
            'sms_type'               => $params['sms_type'],
            'initiated_time'         => $params['initiated_time'],
            'message'                => $params['message'],
            'status'                 => $params['status'],
            'schedule_status'        => $params['schedule_status'],
            'api_gateway_id'         => $params['api_gateway_id'],
            'android_gateway_sim_id' => $params['android_gateway_sim_id'],
            'sim_number'             => $params['sim_number'],
        ]);
    }

    /**
     * @param array $contactNewArray
     * @param GeneralSetting $general
     * @param gateway $smsGateway
     * @param StoreSMSRequest $request
     * @param array $numberGroupName
     * @param array $allAvailableSims
     * @param int $userId
     * @return void
     */
    public function sendSMS(array $contactNewArray, GeneralSetting $general, $smsGateway, StoreSMSRequest $request, array $numberGroupName, array $allAvailableSims, ?int $userId = null): void {
        
        $apiGatewayId = null;

        if ($userId ? auth()->user()->sms_gateway == 1 : $general->sms_gateway == 1) {
           
            $apiGatewayId = (int) $smsGateway->id;

            foreach ($contactNewArray as $value) {

                $log = $this->saveSMSLog($this->prepParams((string)$value, $request, $numberGroupName, $apiGatewayId, null, $userId));
                if ($log->status == 1) {

                    $this->sendSmsByOwnGateway($log, null, count($contactNewArray));
                }
            }

        } else {
            
            if($request->input("android_gateways_id") == "-1") {
                
                foreach ($contactNewArray as $index_key => $number) {
                    
                    foreach ($allAvailableSims as $key => $sim_id){
                      
                        unset($allAvailableSims[$key]);
                        
                        if(empty($allAvailableSims)) {
                            
                            $allAvailableSims = AndroidApiSimInfo::where("status", AndroidApiSimInfo::ACTIVE)->pluck("id")->toArray();
                        }
                        break;
                    }
                    $log = $this->saveSMSLog($this->prepParams((string)$number, $request, $numberGroupName, null, $sim_id, $userId));
                }
                
            } else {

                foreach ($contactNewArray as $value) {

                    $log = $this->saveSMSLog($this->prepParams((string)$value, $request, $numberGroupName, null, $request->sim_id, $userId));
                }
            }
        }
    }
}
