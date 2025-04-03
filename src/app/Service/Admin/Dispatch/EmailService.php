<?php

namespace App\Service\Admin\Dispatch;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Utility\SendEmail;
use App\Jobs\ProcessEmail;
use App\Models\Admin;
use App\Models\CampaignContact;
use App\Models\CommunicationLog;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\CreditLog;
use App\Models\EmailContact;
use App\Models\EmailCreditLog;
use App\Models\EmailLog;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Models\Group;
use App\Models\Template;
use App\Models\User;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Core\FileService;
use App\Service\Admin\Gateway\EmailGatewayService;
use App\Service\MailService;
use App\Services\System\Contact\ContactService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
class EmailService
{
    public CustomerService $customerService;
    public $fileService;
    public $contactService;
    public $emailGatewayService;

    /**
     *
     * @param CustomerService $customerService
     * 
     * @param FileService $customerService
     */
    public function __construct () {

        $this->customerService      = new CustomerService;
        $this->fileService          = new FileService;
        $this->contactService       = new ContactService;
        $this->emailGatewayService  = new EmailGatewayService;
    }

    public function getGroupWhereColumn($column_name, $user_id = null) {

        return ContactGroup::where("user_id", $user_id)->whereHas('contacts', function ($query) use ($column_name) {
            $query->whereNotNull($column_name);
        })->get();
    }

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
     * @param $type
     * 
     * @return Template
     */

     public function getTemplateWithStatusType($status, $type) {

        return Template::where([
            
            'status' => $status,
            'type'   => $type,
        ])->latest()->get();
    }

   /**
     * 
     * @return Gateway
     */
    public function gateways($status, $type = null) {
        
        
        if(!is_null($type)) {
      
            $gateways = $type == StatusEnum::FALSE->status() ? 
                            Gateway::where('status', $status)
                                    ->where("user_id", auth()->user()->id)
                                    ->whereNotNull('mail_gateways')
                                    ->get(['id', 'type', 'name'])
                                    ->groupBy('type')
                                    ->mapWithKeys(function ($items, $type) {
                                        return [
                                            $type => $items->pluck('name', 'id')->toArray()
                                        ];
                                    }):
                            Gateway::where('status', $status)
                                    ->whereNotNull('mail_gateways')
                                    ->whereNull("user_id")
                                    ->get(['id', 'type', 'name'])
                                    ->groupBy('type')
                                    ->mapWithKeys(function ($items, $type) {
                                        return [
                                            $type => $items->pluck('name', 'id')->toArray()
                                        ];
                                    });
            
        } else {

            $gateways = Gateway::where('status', $status)
                                    ->whereNotNull('mail_gateways')
                                    ->whereNull("user_id")
                                    ->get(['id', 'type', 'name'])
                                    ->groupBy('type')
                                    ->mapWithKeys(function ($items, $type) {
                                        return [
                                            $type => $items->pluck('name', 'id')->toArray()
                                        ];
                                    });
        }
        return $gateways;
    }

    public function getLogById($id) {

        return CommunicationLog::where('id', $id)->first();
    }

    private function filterValidEmails(&$meta_data, &$status, &$message)
    {
        $mailService    = new MailService();
        $status    = 'success';
        $message   = translate("Successfully fetched contact from the group");
        $meta_data = array_filter($meta_data, function ($entry) use($mailService) {

            $email = Arr::get($entry, "contact");
            $result = $mailService->verifyEmail($email);
            $validity = Arr::get($result, "valid");
            return $validity; 
        });
        if (empty($meta_data)) {
            $status  = 'error';
            $message = translate("Verified Emails could not be found in the contact list");
        }
    }
    private function checkDailyLimit($user_id, &$meta_data, &$status, &$message, &$pass)
    {
        $user = User::where("id", $user_id)->first();
        $allowed_access = (object) planAccess($user);
        $status  = 'success';
        $message = translate("You have not yet exceeded the daily credit limit for Email messages");
        $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::EMAIL->value, count($meta_data));
       
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
        
        $status    = 'success';
        $message   = translate("Email request has been registered successfully");
        $meta_data = [];
        $pass      = true;
       
        switch ($contact_type) {

            case 'file':

                $contacts  = $data['contacts'];
                [$status, $message, $meta_data] = $this->fileService->readCsv($contacts, array_key_exists('custom_gateway_parameter', $data) ? $data['custom_gateway_parameter'] : null);
                if (site_settings('email_contact_verification') == StatusEnum::TRUE->status()) {

                    $this->filterValidEmails($meta_data, $status, $message);
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
                $meta_data   = $this->contactService->retrieveContacts($type, $contacts, $group_logic, $meta_name, $logic, $logic_range, $user_id, array_key_exists('custom_gateway_parameter', $data) ? $data['custom_gateway_parameter'] : null);
                if (site_settings('email_contact_verification') == StatusEnum::TRUE->status()) {

                    $this->filterValidEmails($meta_data, $status, $message);
                }
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
                        'contact' =>  $contact,
                        'custom_gateway_parameter' => array_key_exists('custom_gateway_parameter', $data) ? $data['custom_gateway_parameter'] : null
                    ]
                ];
                if (site_settings('email_contact_verification') == StatusEnum::TRUE->status()) {

                    $this->filterValidEmails($meta_data, $status, $message);
                }
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
        if(count($meta_data) <= 0 || !$pass) {
            return [
                $status,
                $message
            ];
        }
       
        list($status, $message, $meta_data, $gateway) = $this->emailGatewayService->assignGateway($data['gateway_id'], $meta_data, $user_id);
        
        if($gateway) {
            list($status, $message) = $this->prepData(ServiceType::EMAIL->value, $data, $meta_data, $gateway, $user_id);
        } else {
            
            $status = "error";
            $message = translate("No default gateway was found");
        }
        
        return [
            $status,
            $message
        ];
    }

    /**
     * @param array $request
     * 
     * @return array $status, $message
     *  
     * */ 
    public function statusUpdate(array $data): array {
        
        $status  = 'success';
        $message = translate("Email request has been registered successfully");
        $log = $this->getLogById($data['id']);
        $user_id = null;
        if($log->user_id) {
            $user_id = $log->user_id;
        }
        if($data['status'] == CommunicationStatusEnum::PENDING->value) {
            $meta_data[] = $log->meta_data;
            list($status, $message, $meta_data, $gateway) = $this->emailGatewayService->assignGateway(array_key_exists('gateway_id', $data) ? $data['gateway_id'] :null, $meta_data);
            
            $item_data = $data;
            $item_data['gateway_id'] = $gateway->id;
            $this->prepData(ServiceType::EMAIL->value, $item_data, $meta_data, $gateway, $user_id, true);
           
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


    public function saveLog($data) {
        
        $id = null;
        $fields = [
            'email_from_name',
            'reply_to_address',
            'custom_gateway_parameter'
        ];
        foreach($fields as $field) {

            $data = $this->unsetAdditionalData($data, $field);
        }
        if(array_key_exists('id', $data)) {

            $id = (int)$data['id'];
            unset($data['id']);
        }
        if(array_key_exists('schedule_at', $data) && $data['schedule_at']) {

            $data['status'] = (string)CommunicationStatusEnum::SCHEDULE->value;
        } else {

            $data['status'] = (string)CommunicationStatusEnum::PENDING->value;
        }
        return CommunicationLog::updateOrCreate([
            'id' => $id
        ], $data);
    }


    public function generateUnsubscribeLink($campaign_id, $contact_uid, $channel = ServiceType::EMAIL->value)
    {
        $encrypted_campaign_id = encrypt($campaign_id);
        $encrypted_contact_uid = encrypt($contact_uid);

        $unsubscribeLink = route('unsubscribe', [
            'campaign_id' => $encrypted_campaign_id,
            'contact_id' => $encrypted_contact_uid,
            'channel' => $channel,
        ]);

        return $unsubscribeLink;
    }

    public function prepData($type, $data, $meta_data, $gateway, $user_id = null, $update_status = false) {
    
        $unsubscribeLink = "";
        $status  = 'success';
        $message = translate("Email request has been registered successfully");
        unset($data['contacts']);
        $default = [
            "type"    => $type,
            "user_id" => $user_id
        ];

        if($user_id) {

            $user = User::where("id", $user_id)->first();
            $allowed_access = (object) planAccess($user);
            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::EMAIL->value);
            
            if($has_daily_limit || $update_status) { 
                
                $remaining_email_credits = $user->email_credit;
                $total_contact = count($meta_data);
               
                if (($total_contact > $remaining_email_credits && $user->email_credit != -1) && !$update_status) {

                    $status  = 'error';
                    $message = translate("You do not have sufficient credits to send this message");
                } else {
                    
                    $this->customerService->deductCreditLog($user, (int)$total_contact, ServiceType::EMAIL->value);
                    
                    if($this->emailGatewayService->hasNestedArray($meta_data)) {
                        $content_message = $data['message'];
                        foreach (array_chunk($meta_data, 100) as $chunk) {

                            foreach ($chunk as $key => $value) {
                                if(array_key_exists('campaign_id', $data) && array_key_exists('uid', $value)) {

                                    $unsubscribeLink = $this->generateUnsubscribeLink($data['campaign_id'], $value['uid']);
                                    $value['unsubscribe_link'] = $unsubscribeLink;
                                }
                                $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null;
                                $data['message']    = $this->getFinalContent($content_message, $value, $unsubscribeLink);
                                unset($value['id']);
                                list($value, $data) = $this->addAdditionalMetaData($value, $data);
                                $data['gateway_id'] = array_key_exists('gateway_id', $value) ? $value['gateway_id'] : null;
                                $data['meta_data']  = $value;
                                $data = array_merge($data, $default);
                                
                                
                                $log  = $this->saveLog($data);
                                
                                if ($gateway && !$log->campaign_id) {

                                    $this->send($gateway, count($meta_data), $log);
                                }
                            }
                        }
                        
                    } else {
                      
                        list($meta_data, $data)= $this->addAdditionalMetaData($meta_data, $data);
                        $data['meta_data'] = $meta_data;
                        $data = array_merge($data, $default);
                        $log = $this->saveLog($data);
                    }
                }
            } else {
                
                $status  = 'error';
                $message = translate("You have exceeded the daily credit limit for Email messages");
            }
            
        } else {
            
            if($this->emailGatewayService->hasNestedArray($meta_data)) {
                $content_message = $data['message'];
                foreach($meta_data as $key => $value) {
                    
                    if(array_key_exists('campaign_id', $data) && array_key_exists('uid', $value)) {

                        $unsubscribeLink = $this->generateUnsubscribeLink($data['campaign_id'], $value['uid']);
                        $value['unsubscribe_link'] = $unsubscribeLink;
                    }
                    $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null ;
                    $data['message'] = $this->getFinalContent($content_message, $value, $unsubscribeLink);
                    unset($value['id']);
                    list($value, $data) = $this->addAdditionalMetaData($value, $data);
                    $data['gateway_id'] = array_key_exists('gateway_id', $value) ? $value['gateway_id'] : null;
                    $data['meta_data'] = $value;
                    $data = array_merge($data, $default);
                   
                    
                    $log = $this->saveLog($data);
                
                    if($gateway && !$log->campaign_id) {
        
                        $this->send($gateway, count($meta_data), $log);
                    }
                }
            } else {
                list($meta_data, $data)= $this->addAdditionalMetaData($meta_data, $data);
                $data['meta_data'] = $meta_data;
                $data = array_merge($data, $default);
                $log = $this->saveLog($data);
            }
        }
        
        return [$status, $message];
    }

    public function addAdditionalMetaData($meta_data, $data) {
        
        $fields = [
            'email_from_name',
            'reply_to_address'
        ];
        
        foreach($fields as $field) {

            $meta_data[$field] = array_key_exists($field, $meta_data) ? $meta_data[$field] : $data[$field];
            
        }
        return [$meta_data, $data];
    } 

    public function unsetAdditionalData($data, $unset_field) {

        unset($data[$unset_field]);
        return $data;
    }

    public function send($gateway, $total_contact, $log) {
        
        
        $scheduledTime = Carbon::parse($log->schedule_at);
       
        if ($log->schedule_at && $scheduledTime->isFuture()) {
            
            $log->gateway_id = property_exists($gateway, 'id') ? $gateway->id : null;
            $log->gateway_id = property_exists($gateway, 'id') ? $gateway->id : null;
            $log->status = CommunicationStatusEnum::SCHEDULE->value;
            $log->save();
            return;

        } else {
           
            if ($total_contact > 1) {
                
                
                ProcessEmail::dispatch($log, $gateway);
               
            } else {
                
                list($subject, $message, $email_to, $email_from_name, $email_reply_to) = $this->getEmailData($log, $gateway);
                
                if($gateway->type == 'smtp') {
    
                    SendEmail::sendSMTPMail($email_to, $email_reply_to, $subject, $message, $log,  $gateway, $email_from_name);
                }
                elseif($gateway->type == "mailjet") {
    
                    SendEmail::sendMailJetMail($email_to, $subject, $message, $log, $gateway, $email_reply_to, $email_from_name);
                }
                elseif($gateway->type == "aws") {
    
                    SendEmail::sendSesMail($email_to, $subject, $message, $log, $gateway); 
                }
                elseif($gateway->type  == "mailgun") {
                    
                    SendEmail::sendMailGunMail($email_to, $subject, $message, $log, $gateway); 
                }
                elseif($gateway->typ == "sendgrid") {
    
                    SendEmail::sendGrid($gateway->address, $email_from_name, $email_to, $subject, $message, $log, @$gateway->mail_gateways->secret_key);
                }
            }
        }
       
    }

    /**
     * @param CommunicationLog $log
     * @param $status
     * @param $errorMessage
     * @return void
     */
    public static function updateEmailLogAndCredit(CommunicationLog $log, $status, $errorMessage = null): void {
        
        $log->status           = $status;
        $log->response_message = !is_null($errorMessage) ? $errorMessage : null;
        $log->save();
        $user = User::find($log->user_id);
        $user->fresh();
        if ($user && $status == CommunicationStatusEnum::FAIL->value) {

            CustomerService::addedCreditLog($user, 1, ServiceType::EMAIL->value);
        }
    }

    public function getEmailData($log, $gateway) {
        
        $subject = $log->message['subject'];
        $message = $log->message['message_body'];
        $email_to = $log->meta_data['contact'];
        $email_from_name = $log->meta_data['email_from_name'] ?? $gateway->name;
        
        $email_reply_to = $log->meta_data['reply_to_address'] ?? $gateway->address;
        return [

            $subject, $message, $email_to, $email_from_name, $email_reply_to
        ];
    }

    /**
     * @param mixed $value
     * 
     * @param array $message
     * 
     * @return string
     */
    public function getFinalContent(array $message, mixed $value, string $unsubscribeLink = null): array {

        $message['message_body'] = buildDomDocument(textSpinner($message['message_body']));
        foreach ($value as $key => $val) {
            if($key != "custom_gateway_parameter") {
               
                $message = str_replace('{{' . $key . '}}', $val, $message);
            }
            if($unsubscribeLink) {
               
                $message = str_replace('%7B%7Bunsubscribe_link%7D%7D', $unsubscribeLink, $message);
            }
        }
        return $message;
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

                        list($status, $message, $meta_data, $gateway) = $this->emailGatewayService->assignGateway(array_key_exists('gateway_id', $data) ? $data['gateway_id'] :null, $item->meta_data);
                        $item_data = $data;
                        $item_data['id'] = $id;
                        $item_data['schedule_at'] = null;
                        $this->prepData(ServiceType::EMAIL->value, $item_data, $meta_data, $gateway);
                        if($gateway) {
                            $this->send($gateway, count($ids), $item);
                        }
                        if($item->status == CommunicationStatusEnum::DELIVERED->value) {
                            $meta_data = $item->meta_data;
                            $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
                            $item->meta_data = $meta_data;
                        }
                        $item->status = $statusValue;
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












    /**
     * @param Request $request
     * @param array $allEmail
     * @param $userId
     * @return void
     */
    public function processEmail(Request $request, array &$allContactNumber, $userId = null): void {
      
        if($request->has('email')) {

            $email = Contact::query();
            $email->whereIn('id', $request->input('email'));

           
            if (!is_null($userId)) {

                $email->where('user_id', $userId);
            } else {

                $email->whereNull('user_id');
            }
            $emailArray = $email->pluck('email_contact','id')->toArray();
           
            $allContactNumber[] = array_values($emailArray) + array_diff($request->input('email') , $emailArray);
        }
    }

    public function searchEmailLog($search, $searchDate): \Illuminate\Database\Eloquent\Builder {

        $emailLogs = EmailLog::query();
        if (!empty($search)) {
            $emailLogs->whereHas('sender',function ($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                    ->orWhere('to', 'like', "%$search%");
            });
        }
        if (!empty(request()->input('status'))) {
            $status = match (request()->input('status')){
                'pending'   => [1],
                'schedule'  => [2],
                'fail'      => [3],
                'delivered' => [4],
                default     => [1,2,3,4],
            };
            $emailLogs->whereIn('status',$status);
        }
        if (!empty($searchDate)) {

            $dateRange = explode('-', $searchDate);
            $firstDate = Carbon::createFromFormat('m/d/Y', trim($dateRange[0]))->startOfDay();
            $lastDate  = isset($dateRange[1]) ? Carbon::createFromFormat('m/d/Y', trim($dateRange[1]))->endOfDay() : null;
            if ($firstDate) {
                $emailLogs->whereDate('created_at', '>=', $firstDate);
            }
            if ($lastDate) {
                $emailLogs->whereDate('created_at', '<=', $lastDate);
            }
        }

        return $emailLogs;
    }


    /**
     * @param Request $request
     * @param array $allEmail
     * @param array $emailGroupName
     * @param $userId
     * @return void
     */
    public function processEmailGroup(Request $request, array &$allEmail, array &$emailGroupName, $userId = null): void
    {
        
        if($request->has('email_group_id')){
            $emailContact = EmailContact::query();
            $emailContact->whereIn('email_group_id', $request->input('email_group_id'));

            if (!is_null($userId)) {
                $emailContact->where('user_id', $userId);
            } else {
                $emailContact->whereNull('user_id');
            }

            $allEmail[]     = $emailContact->pluck('email')->toArray();
            $emailGroupName = $emailContact->pluck('name', 'email')->toArray();
        }
    }


    /**
     * @param Request $request
     * @param array $allEmail
     * @param array $emailGroupName
     * @return void
     */
    public function processEmailFile(Request $request, array &$allEmail, array &$emailGroupName): void
    {
       
        if($request->has('file')) {

           
            $service   = new FileProcessService();
            $extension = strtolower($request->file('file')->getClientOriginalExtension());

            if($extension == "csv") {

                $response       =  $service->processCsv($request->file('file'));
                
                $allEmail[]     = array_keys($response);
                $emailGroupName = $emailGroupName + $response;
            };

            if($extension == "xlsx") {

                $response       = $service->processExel($request->file('file'));
                
                $allEmail[]     = array_keys($response);
                $emailGroupName = $emailGroupName + $response;
            }
        }
    }


    /**
     * @param array $allEmail
     * @return array
     */
    public function flattenAndUnique(array $allContactNumber): array
    {
        $newArray = [];
        foreach ($allContactNumber as $childArray) {
            foreach ($childArray as $value) {
                $newArray[] = $value;
            }
        }
        return array_unique($newArray);
    }


    /**
     * @param array $emailAllNewArray
     * @param Gateway $emailMethod
     * @param StoreEmailRequest $request
     * @param array $emailGroupName
     * @param null $userId
     * @return void
     */
    public function sendEmail(array $emailAllNewArray, Gateway $emailMethod, StoreEmailRequest $request, array $emailGroupName, $userId = null): void
    {
        foreach($emailAllNewArray as $value) {
            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                continue;
            }
            $prepare  = $this->prepParams($value, $request, (int)$emailMethod->id, $emailGroupName, $userId);
            $emailLog = $this->save($prepare);
            if ($request->input('schedule') == 1 && $emailLog->status == 1) { 
                
                if(count($emailAllNewArray) > 1) {
                    
                    ProcessEmail::dispatch($emailLog);
                } else {
               
                $general       = GeneralSetting::first();
                $emailTo       = $emailLog->to;
                $subject       = $emailLog->subject;
                $messages      = $emailLog->message;
                $emailFrom     = $general->mail_from;
                $emailFromName = $general->site_name;
                $emailReplyTo  = $general->mail_from;
                $emailMethod   = Gateway::whereNotNull('mail_gateways')->where('status',1)->where('id', $emailLog->sender_id)->first();
                $user          = User::where('id', $emailLog->user_id)->first();

                if(is_null($emailLog->user_id)) { 

                    $admin          = Admin::first();
                    $emailFrom      = $emailMethod->address;
                    $emailFromName  = is_null($emailLog->from_name) ? $emailMethod->name : $emailLog->from_name;
                    $emailReplyTo   = is_null($emailLog->reply_to_email) ? $$emailMethod->email : $emailLog->reply_to_email;
                }

                if($user) {

                    $emailMethod    = Gateway::whereNotNull('mail_gateways')->where('status',1)->where('id', $emailLog->sender_id)->firstOrFail();
                    $emailFrom      = $emailMethod->address;
                    $emailFromName  = $emailLog->from_name ?? $emailMethod->name;
                    $emailReplyTo   = $emailLog->reply_to_email ?? $emailMethod->address;
                }

                if($emailLog->sender->type == 'smtp') {

                    SendEmail::sendSMTPMail($emailTo, $emailReplyTo, $subject, $messages, $emailLog,  $emailMethod, $emailFromName);
                }
                elseif($emailLog->sender->type == "mailjet") {

                    SendEmail::sendMailJetMail($emailTo, $subject, $messages, $emailLog, $emailMethod, $emailReplyTo, $emailFromName);
                }
                elseif($emailLog->sender->type == "aws") {

                    SendEmail::sendSesMail($emailTo, $subject, $messages, $general, $emailMethod); 
                }
                elseif($emailLog->sender->type  == "mailgun") {
                    
                    SendEmail::sendMailGunMail($emailTo, $subject, $messages, $general, $emailMethod); 
                }
                elseif($emailLog->sender->typ == "sendgrid") {

                    SendEmail::sendGrid($emailFrom, $emailFromName, $emailTo, $subject, $messages, $emailLog, @$emailMethod->mail_gateways->secret_key);
                }
            }
                
            } 
        }
    }

    /**
     * @param string $value
     * @param StoreEmailRequest $request
     * @param int $emailMethodId
     * @param array $emailGroupName
     * @param $userId
     * @return array
     */
    public function prepParams(string $value, StoreEmailRequest $request, int $emailMethodId, array $emailGroupName, $userId): array
    {
        $setTimeInDelay = Carbon::now();
        
       
        if($request->input('schedule') == 2){
            $setTimeInDelay = $request->input('schedule_date');
        }

        return  [
            'from_name'       => $request->input('from_name'),
            'reply_to_email'  => $request->input('reply_to_email'),
            'sender_id'       => $emailMethodId,
            'to'              => $value,
            'user_id'         => $userId,
            'initiated_time'  => $setTimeInDelay,
            'subject'         => $request->input('subject'),
            'message'         => $this->getFinalContent($value, $emailGroupName, $request->input('message')),
            'status'          => $request->input('schedule', EmailLog::PENDING),
            'schedule_status' => $request->input('schedule'),
        ];
    }

    /**
     * @param array $params
     * @return EmailLog
     */
    public function save(array $params): EmailLog
    {
        return EmailLog::create([
            'from_name'       => $params['from_name'],
            'user_id'         => $params['user_id'],
            'reply_to_email'  => $params['reply_to_email'],
            'sender_id'       => $params['sender_id'],
            'to'              => $params['to'],
            'initiated_time'  => $params['initiated_time'],
            'subject'         => $params['subject'],
            'message'         => $params['message'],
            'status'          => $params['status'],
            'schedule_status' => $params['schedule_status'],
        ]);
    }
}
