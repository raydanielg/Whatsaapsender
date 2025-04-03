<?php

namespace App\Service\Admin\Dispatch;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Utility\SendWhatsapp;
use App\Models\User;
use App\Models\WhatsappCreditLog;
use App\Models\WhatsappLog;
use App\Rules\MessageFileValidationRule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Jobs\ProcessWhatsapp;
use App\Models\CommunicationLog;
use App\Models\ContactGroup;
use Illuminate\Support\Facades\DB;
use App\Models\Gateway;
use App\Models\Group;
use App\Models\Template;
use App\Models\WhatsappDevice;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Core\FileService;
use App\Service\Admin\Gateway\SmsGatewayService;
use App\Service\Admin\Gateway\WhatsappGatewayService;
use App\Services\System\Contact\ContactService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class WhatsAppService
{
    public CustomerService $customerService;
    public $fileService;
    public $contactService;
    public $whatsappGatewayService;

    /**
     * __construct
     *
     */
    public function __construct () {

        $this->customerService          = new CustomerService;
        $this->fileService              = new FileService;
        $this->contactService           = new ContactService;
        $this->whatsappGatewayService   = new WhatsappGatewayService;
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
     * @param string $column_name
     * 
     * @return Group
     */
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

     public function getTemplateWithStatusType($status, $type) {

        return Template::where([
            
            'status' => $status,
            'type'   => $type,
        ])->latest()->get();
    }

    /**
     * 
     * @param $status
     * 
     * @return AndroidApi
     */
    public function gateways($type, $user_id = null) {

        $gateways = WhatsappDevice::where('status', 'connected')
            ->where("user_id", $user_id)
            ->where('type', $type)
            ->latest()
            ->pluck('name', 'id')
            ->toArray();
        
        return $gateways;
    }

    private function checkDailyLimit($user_id, &$meta_data, &$status, &$message, &$pass)
    {
        $user = User::where("id", $user_id)->first();
        $allowed_access = (object) planAccess($user);
        $status  = 'success';
        $message = translate("You have not yet exceeded the daily credit limit for WhatsApp messages");
        $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::WHATSAPP->value, count($meta_data));
        
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

        $postData = $this->findAndUploadFile(request());
        if($postData) {
            $data['file_info'] = $postData;
        }
        $status    = 'success';
        $message   = translate("Whatsapp request has been registered successfully");
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
        
        list($status, $message, $meta_data, $gateway) = $this->whatsappGatewayService->assignGateway($data['method'], $data['gateway_id'], $meta_data, $user_id);
        
        if($gateway) {

            list($status, $message) = $this->prepData($data, $meta_data, $gateway, $user_id);
            
        } else {
            
            $status = "error";
            $message = translate("No connected gateway was found");
        }
        
        return [
            $status,
            $message
        ];
    }

    public function prepForLog($fieldsToKeep, $data) {
        
        foreach($data as $key => $value) {
            if (!in_array($key, $fieldsToKeep)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    public function campaignRelatedField() {

        return [
            'schedule_at',
            'whatsapp_template_id',
            'message',
            'gateway_id',
            'contact_id',
            'type',
            'user_id',
            'campaign_id',
            'meta_data',
            'file_info',
            'schedule_at',
        ];
    }

    public function getTemplateData($request, $template) {

        
        $template_message = $template["template_data"]["components"];
        $request_data     = $request->all();
        $matches = []; $i = 0; $message = []; $data = [];
        
        foreach ($request_data as $request_key => $request_value) {
            
            if (str_contains($request_key, "_placeholder_")) {

                preg_match('/([a-z]+)_placeholder_(\d+)/', $request_key, $match);
                $matches[]          = $match;
                $data[$request_key] = $request_value;
            }
            if (str_contains($request_key, "_header_media")) {

                $fileType = explode('_', $request_key)[0];
                $fileLink = "";
                
                if ($fileType == "image") { $fileLink = storeCloudMediaAndGetLink('image_header_media', $request->file('image_header_media')); } 
                elseif ($fileType == "video") { $fileLink = storeCloudMediaAndGetLink('video_header_media', $request->file('video_header_media')); } 
                elseif ($fileType == "document") { $fileLink = storeCloudMediaAndGetLink('document_header_media', $request->file('document_header_media')); }

                preg_match('/([a-z]+)_header_media/', $request_key, $match);
                $match[]            = "header_media"; 
                $match[]            = $fileLink; 
                $matches[]          = $match;
                $data[$request_key] = $request_value;
            }
            if (str_contains($request_key, "_button_")) {

                preg_match('/([a-z]+)_button_(\d+)/', $request_key, $match);
            
                $match[]   = $request_value; 
                $matches[] = $match;
                $data[]    = $match; 
            }
            if (str_contains($request_key, "flow_")) {
                preg_match('/(flow)_([a-z]+)/', $request_key, $match); 
                $match[]   = $request_value; 
                $match[1] = "flow"; 
                $matches[] = $match;
                $data[]    = $match; 
            }
        }
        array_column($matches, 1);
        $k = 0;
        $t = 0;
        foreach ($matches as $value) {
            
            $type                 = strtoupper($value[1]); 
            $number               = $value[2];
            
            $template_message_key = array_search($type, array_column($template_message, 'type'));
            
            if ($template_message_key !== false || preg_match('/button/', $value[0]) || preg_match('/_header_media/', $value[0]) ||  preg_match('/flow_cloud/', $value[0])) {
                
                if ($value[1] == "header") {
                    
                    foreach($template_message[$template_message_key]['example']["$value[1]_text"] as $template_key => $template_value) {
                        
                        $message[$template_message_key]["type"]         = strtolower($template_message[$template_message_key]["type"]);
                        $message[$template_message_key]["parameters"][] = [
                            "type" => strtolower($template_message[$template_message_key]["format"]),
                            strtolower($template_message[$template_message_key]["format"]) => $request_data["$value[1]_placeholder_$template_key"]
                        ];
                    }
                } elseif ($value[1] == "reply") {

                    $message[] = [
                        "type"       => "button",
                        "sub_type"   => "QUICK_REPLY",
                        "index"      => $value[2],
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => $value[3],
                            ]
                        ],
                    ];

                } elseif ($value[1] == "code") {
                    
                    $message[3] = [
                        "type"       => "button",
                        "sub_type"   => "COPY_CODE",
                        "index"      => $value[2],
                        "parameters" => [
                            [
                                "type" => "coupon_code",
                                "coupon_code" => $value[3],
                            ]
                        ],
                    ];
                    
                } elseif ($value[1] == "url") {
                    
                    $message[4] = [
                        "type"       => "button",
                        "sub_type"   => "URL",
                        "index"      => $value[2],
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => substr($value[3], strlen($template_message[$t]['buttons'][0]['example'][0] ?? '')),
                            ]
                        ],
                    ];
                } elseif ($value[1] == "flow") {
                    
                    $flow_key = null;
                    foreach ($template_message as $index => $item) {
                        if (isset($item['buttons'])) {
                            foreach ($item['buttons'] as $button) {
                                if ($button['type'] === 'FLOW') {
                                    $flow_key = $index; 
                                    break 2; 
                                }
                            }
                        }
                    }
                    $message[3] = [
                        "type"       => "button",
                        "sub_type"   => "FLOW",
                        "index"      => 0,
                        "parameters" => [
                            [
                                "type" => "action",
                                "action" => [
                                    "flow_token" => "unused",
                                ]
                            ]
                        ],
                    ];
                    
                } elseif ($value[2] === 'header_media') {

                    $message[] = [
                        "type"       => "header",
                        "parameters" => [
                            [
                                "type"  => strtolower($value[1]),
                                strtolower($value[1]) => [
                                    "link" => $value[3],
                                ],
                            ]
                        ],
                    ];
                } else {
                   
                    foreach($template_message[$template_message_key]['example']["$value[1]_text"] as $template_key => $template_value) {
                        
                        $message[$template_message_key]["type"]         = strtolower($template_message[$template_message_key]["type"]);
                        $message[$template_message_key]["parameters"][] = [
                            "type" => "text",
                            "text" => $data["body_placeholder_$k"]
                        ];
                        $k++;
                    }
                  
                } 
            }
            $t++;
        }
        return $message;
    }
    public function transformMessageBody($messageBody) {

        return array_values($messageBody);
    }
    public function prepData($data, $meta_data, $gateway, $user_id = null) {
        
        
        $status  = 'success';
        $notify_message = translate("WhatsApp request has been registered successfully");
        $default = [
            "type"    => ServiceType::WHATSAPP->value,
            "user_id" => $user_id,
        ];
        if($user_id) {

            $user = User::where("id", $user_id)->first();
            $allowed_access = (object) planAccess($user);
            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::WHATSAPP->value);
            if($has_daily_limit) {
                $remaining_whatsapp_credits = $user->whatsapp_credit;
                $total_message = count(str_split($data["message"]["message_body"], site_settings("whatsapp_word_count")));
                $total_contact = count($meta_data);
                $total_credits = $total_contact * $total_message;
                if ($total_credits > $remaining_whatsapp_credits && $user->whatsapp_credit != -1) {

                    $status  = 'error';
                    $notify_message = translate("You do not have sufficient credits for send message");
                } else {

                    $this->customerService->deductCreditLog($user, (int)$total_credits, ServiceType::WHATSAPP->value);

                    if($data['method'] == "cloud_api") {
            
                        if($this->whatsappGatewayService->hasNestedArray($meta_data)) {
                           
                            $template = Template::find($data["whatsapp_template_id"]);
                            $message = $this->getTemplateData(request(), $template);
                            $data['message']['message_body'] = $this->transformMessageBody($message);
                                           
                            $i = 1;
                            foreach (array_chunk($meta_data, 100) as $chunk) {

                                foreach ($chunk as $key => $value) {
                                    $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null;
                                    unset($value['id']);
                                    $data['meta_data'] = $value;
                                    $data['gateway_id'] = array_key_exists('gateway_id', $value) ? $value['gateway_id'] : $gateway->id;
                                    $data      = array_merge($data, $default);
                                    $item_data = $this->prepForLog($this->campaignRelatedField(), $data);
                                    $log = $this->saveLog($item_data);
                                    if ($gateway && !@$log?->campaign_id) {
                                        
                                        $this->send($gateway, count($meta_data), $log, $i);
                                        $i++;
                                    }
                                }
                            }
                        } else {
                
                            $data['meta_data'] = $meta_data;
                            $data['gateway_id'] = $gateway->id;
                            $data = array_merge($data, $default);
                            $log = $this->saveLog($data);
                        }
            
                    } else {
                        
                        if($this->whatsappGatewayService->hasNestedArray($meta_data)) {
            
                            $i = 1;
                            $content_message = $data['message'];
                            foreach($meta_data as $key => $value) {
                                
                                $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null ;
                                $data['message'] = $this->getFinalContent($content_message, $value);
                                unset($value['id']);
                                $data['meta_data'] = $value;
                                $data = array_merge($data, $default);
                                $data['gateway_id'] = array_key_exists('gateway_id', $value) ? $value['gateway_id'] : $gateway->id;
                                $item_data = $this->prepForLog($this->campaignRelatedField(), $data);
                                
                                $log = $this->saveLog($item_data);
                                if($gateway && !@$log?->campaign_id) {
                                    
                                    $this->send($gateway, count($meta_data), $log, $i);
                                    $i++;
                                }
                            }
                        } else {
                
                            $data['meta_data'] = $meta_data;
                            $data['gateway_id'] = $gateway->id;
                            $data = array_merge($data, $default);
                            $log = $this->saveLog($data);
                            
                        }
                    }
                }
            } else {
                
                $status  = 'error';
                $notify_message = translate("You have exceeded the daily credit limit for WhatsApp messages");
            }
        } else {
            
            if($data['method'] == "cloud_api") {
            
                if($this->whatsappGatewayService->hasNestedArray($meta_data)) {
                   
                    $template = Template::find($data["whatsapp_template_id"]);
                    $message = $this->getTemplateData(request(), $template);
                    $data['message']['message_body'] = $this->transformMessageBody($message);
                                   
                    $i = 1;
                    foreach($meta_data as $key => $value) {
        
                        $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null ;
                        unset($value['id']);
                        $data['meta_data'] = $value;
                        $data['gateway_id'] = array_key_exists('gateway_id', $value) ? $value['gateway_id'] : $gateway->id;
                        $data = array_merge($data, $default);
                        $item_data = $this->prepForLog($this->campaignRelatedField(), $data);
                        
                        $log = $this->saveLog($item_data);
    
                        if($gateway && !@$log?->campaign_id) {
                            
                            $this->send($gateway, count($meta_data), $log, $i);
                            $i++;
                        }
                    }
                } else {
        
                    $data['meta_data'] = $meta_data;
                    $data['gateway_id'] = $gateway->id;
                    $data = array_merge($data, $default);
                    $log = $this->saveLog($data);
                }
    
            } else {
                
                if($this->whatsappGatewayService->hasNestedArray($meta_data)) {
    
                    $i = 1;
                    $content_message = $data['message'];
                    foreach($meta_data as $key => $value) {
        
                        $data['contact_id'] = array_key_exists('id', $value) ? $value['id'] : null ;
                        $data['message'] = $this->getFinalContent($content_message, $value);
                        unset($value['id']);
                        $data['meta_data'] = $value;
                        $data = array_merge($data, $default);
                        $data['gateway_id'] = array_key_exists('gateway_id', $value) ? $value['gateway_id'] : $gateway->id;
                        $item_data = $this->prepForLog($this->campaignRelatedField(), $data);
                        
                        $log = $this->saveLog($item_data);
                        if($gateway && !@$log?->campaign_id) {
                            
                            $this->send($gateway, count($meta_data), $log, $i);
                            $i++;
                        }
                    }
                } else {
        
                    $data['meta_data'] = $meta_data;
                    $data['gateway_id'] = $gateway->id;
                    $data = array_merge($data, $default);
                    $log = $this->saveLog($data);
                }
            }
        }
        
        return [$status, $notify_message];
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

    public function saveLog($data) {
        
        $id = null;
        if(array_key_exists('id', $data)) {

            $id = (int)$data['id'];
            unset($data['id']);
        }
        if(array_key_exists('schedule_at', $data) && $data['schedule_at']) {

            $data['status'] = (string)CommunicationStatusEnum::SCHEDULE->value;
        } else {

            $data['status'] = (string)CommunicationStatusEnum::PENDING->value;
        }
        // return $id 
        // ? CommunicationLog::where('id', $id)
        //                         ->update($data) 
        // : CommunicationLog::create($data);
        
        return CommunicationLog::updateOrCreate([
            'id' => $id
        ], $data);
        
    }

    public function send($gateway, $total_contact, $log, $i = null) {
        
        if(@$log?->schedule_at) {
           
            $scheduledTime = Carbon::parse($log->schedule_at);
            if ($scheduledTime->isFuture()) {
                
                $log->gateway_id = $gateway->id;
                $log->status = CommunicationStatusEnum::SCHEDULE->value;
                $log->save();
                return;
            } 
        } else {
          
            if ($total_contact > 1) {

                if($gateway->type == StatusEnum::FALSE->status()) {
                    
                    $delay = rand($gateway->credentials["min_delay"], $gateway->credentials["max_delay"]) * $i;
                    ProcessWhatsapp::dispatch($log, $gateway)->delay(now()->addSeconds($delay));
                } else {
    
                    ProcessWhatsapp::dispatch($log, $gateway);
                }
            } else {
    
                if($gateway->type == StatusEnum::FALSE->status()) {
                    
                    $delay = rand($gateway->credentials["min_delay"], $gateway->credentials["max_delay"]) * $i;
                    SendWhatsapp::sendNodeMessages($log, null);
                } else {
                    
                    SendWhatsapp::sendCloudApiMessages($log, null);
                }
            }
        }
    }

     /**
     * @param array $request
     * 
     * @return array $status, $message
     *  
     * */ 
    public function statusUpdate(array $data, $log): array {
        
        $status  = 'success';
        $message = translate("WhatsApp request has been registered successfully");
        $log = CommunicationLog::where("id", $data['id'])->first();
        $gateway = WhatsappDevice::where("id", $log->gateway_id)->first();
        $user = User::find($log->user_id);
        if(!$gateway) {
            return [
                "error",
                translate("Can not access WhatsApp Device")
            ];
        }
        if($data['status'] == CommunicationStatusEnum::PENDING->value) {
            $meta_data[] = $log->meta_data;
            list($status, $message, $meta_data, $gateway) = $this->whatsappGatewayService->assignGateway($gateway->type == StatusEnum::TRUE->status() ? "cloud_api" : "without_cloud_api", $gateway->id, $meta_data, null);
            $log->status = CommunicationStatusEnum::PENDING->value;
            $log->gateway_id = $gateway->id;
            if($log->user_id) {
                $total_credits = count(str_split($log->message["message_body"], site_settings("whatsapp_word_count")));
                if ($user->whatsapp_credit != -1 && $total_credits > $user->whatsapp_credit) {

                    $status  = 'error';
                    $message = translate("User does not have sufficient WhatsApp Credit");
                } else {
                    $this->customerService->deductCreditLog($user, (int)$total_credits, ServiceType::WHATSAPP->value);
                    $log->update();
            
                    if($gateway) {
                        
                        $this->send($gateway, count($meta_data), $log);
                    }
                } 
            } else {
                $log->update();
                if($gateway) {
                    $this->send($gateway, count($meta_data), $log);
                }
            }
            
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


    public function fileValidationRule(Request $request): void {

        $files   = ['document', 'audio', 'image', 'video'];
        $message = 'message';
        $rules   = 'required';

        foreach ($files as $file) {

            if ($request->hasFile($file)) {

                $message = $file;
                $rules = ['required', new MessageFileValidationRule($file)];
                break;
            }
        }

        $request->validate([
            $message => $rules,
        ]);
    }

    /**
     * @param $request
     * @return array|null
     */
    public function findAndUploadFile($request): ?array {
        
        $fileTypes = ['image', 'document', 'audio', 'video', 'others'];
        
        foreach ($fileTypes as $fileType) {
            
            if ($request->hasFile($fileType) == 'others') {

                $file     = $request->file($fileType);
                $fileName = uniqid().time().'.'.$file->getClientOriginalExtension();
                $path     = filePath()['whatsapp']['path_'.$fileType];
                
                if(!file_exists($path)) {

                    mkdir($path, 0777, true);
                }
                try {
                    $file->move($path, $fileName);
                    
                    return [

                        'type'     => $fileType,
                        'url_file' => $path . '/' . $fileName,
                        'name'     => $fileName
                    ];
                } catch (\Exception $e) {

                    return [];
                }
            }
        }

        return [];
    }

    /**
     * @param Request $request
     * @param array $contactNewArray
     * @param int $wordLength
     * @param array $numberGroupName
     * @param array $whatsappGateway
     * @param int|null $userId
     * @return void
     */
    public function save(Request $request, array $contactNewArray, int $wordLength, array $numberGroupName, array $allAvailableWaGateway, ?int $userId = null, mixed $templateData = null, mixed $allowed_access = null): void {
        
        $postData       = $this->findAndUploadFile(request());
        $setTimeInDelay = Carbon::now();
        
        if ($request->input('schedule') == 2) {    

            $setTimeInDelay = $request->input('schedule_date');
        }

        if($request->input("cloud_api") == "true") {
            
            $template_message = $templateData["template_information"];
            $request_data     = $request->all();
            $matches = []; $i = 0; $message = []; $data = [];
            
            foreach ($request_data as $request_key => $request_value) {

                if (str_contains($request_key, "_placeholder_")) {

                    preg_match('/([a-z]+)_placeholder_(\d+)/', $request_key, $match);
                    $matches[]          = $match;
                    $data[$request_key] = $request_value;
                }
                if (str_contains($request_key, "_header_media")) {

                    $fileType = explode('_', $request_key)[0];
                    $fileLink = "";

                    if ($fileType == "image") { $fileLink = storeCloudMediaAndGetLink('image_header_media', $request->file('image_header_media')); } 
                    elseif ($fileType == "video") { $fileLink = storeCloudMediaAndGetLink('image_header_media', $request->file('image_header_media')); } 
                    elseif ($fileType == "document") { $fileLink = storeCloudMediaAndGetLink('image_header_media', $request->file('image_header_media')); }

                    preg_match('/([a-z]+)_header_media/', $request_key, $match);
                    $match[]            = "header_media"; 
                    $match[]            = $fileLink; 
                    $matches[]          = $match;
                    $data[$request_key] = $request_value;
                }
                if (str_contains($request_key, "_button_")) {

                    preg_match('/([a-z]+)_button_(\d+)/', $request_key, $match);
                
                    $match[]   = $request_value; 
                    $matches[] = $match;
                    $data[]    = $match; 
                }
            }
            array_column($matches, 1);
            $k = 0;
            
            foreach ($matches as $value) {
               
                $type                 = strtoupper($value[1]); 
                $number               = $value[2];
                $template_message_key = array_search($type, array_column($template_message, 'type'));
                
                if ($template_message_key !== false || preg_match('/button/', $value[0]) || preg_match('/_header_media/', $value[0])) {
                    
                    if ($value[1] == "header") {
                    
                        foreach($template_message[$template_message_key]['example']["$value[1]_text"] as $template_key => $template_value) {
                            
                            $message[$template_message_key]["type"]         = strtolower($template_message[$template_message_key]["type"]);
                            $message[$template_message_key]["parameters"][] = [
                                "type" => strtolower($template_message[$template_message_key]["format"]),
                                strtolower($template_message[$template_message_key]["format"]) => $request_data["$value[1]_placeholder_$template_key"]
                            ];
                        }
                    } elseif ($value[1] == "reply") {

                        $message[] = [
                            "type"       => "button",
                            "sub_type"   => "QUICK_REPLY",
                            "index"      => $value[2],
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $value[3],
                                ]
                            ],
                        ];

                    } elseif ($value[1] == "code") {
                        
                        $message[3] = [
                            "type"       => "button",
                            "sub_type"   => "COPY_CODE",
                            "index"      => $value[2],
                            "parameters" => [
                                [
                                    "type" => "coupon_code",
                                    "coupon_code" => $value[3],
                                ]
                            ],
                        ];
                        
                    } elseif ($value[1] == "url") {
                        
                        $message[3] = [
                            "type"       => "button",
                            "sub_type"   => "URL",
                            "index"      => $value[2],
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $value[3],
                                ]
                            ],
                        ];
                        
                    } elseif ($value[2] === 'header_media') {

                        $message[] = [
                            "type"       => "header",
                            "parameters" => [
                                [
                                    "type"  => strtolower($value[1]),
                                    strtolower($value[1]) => [
                                        "link" => $value[3],
                                    ],
                                ]
                            ],
                        ];
                    } else {
                        
                        foreach($template_message[$template_message_key]['example']["$value[1]_text"] as $template_key => $template_value) {
                            
                            $message[$template_message_key]["type"]         = strtolower($template_message[$template_message_key]["type"]);
                            $message[$template_message_key]["parameters"][] = [
                                "type" => "text",
                                "text" => $data["body_placeholder_$k"]
                            ];
                            $k++;
                        }
                    } 
                }
            }
            

            foreach ($contactNewArray as $index_key => $number) {
                    
                $contact   = filterContactNumber($number);
                $value     = array_key_exists($contact, $numberGroupName) ? $numberGroupName[$contact] : $contact;
                $content   = $template_message;
            
                $log                  = new WhatsappLog();
                $log->user_id         = $userId;
                $log->whatsapp_id     = $allAvailableWaGateway[array_key_first($allAvailableWaGateway)];
                $log->template_id     = $templateData->id;
                $log->to              = $contact;
                $log->mode            = (boolean) WhatsappLog::CLOUD_API;
                $log->initiated_time  = $setTimeInDelay;
                $log->message         = json_encode(array_values($message), JSON_UNESCAPED_SLASHES);
                $log->word_length     = $wordLength;
                $log->status          = WhatsappLog::PENDING;
                $log->file_info       = count($postData) > 0 ? $postData : null;
                $log->schedule_status = $request->input('schedule');
                $log->save();
                
                if (count($contactNewArray) == 1 && $request->input('schedule') == WhatsappLog::PENDING) { 

                    SendWhatsapp::sendCloudApiMessages($log, $wordLength);
                    
                } elseif(count($contactNewArray) > 1) {
                    
                    ProcessWhatsapp::dispatch($log);
                }
            }
            
        } else {
    
            $setWhatsAppGateway = $allAvailableWaGateway;
            $i = 1; $addSecond  = 50;

            if($request->input("whatsapp_device_id") == "-1") {
        
                foreach ($contactNewArray as $index_key => $number) {

                    $contact = filterContactNumber($number);
                    $value   = array_key_exists($contact, $numberGroupName) ? $numberGroupName[$contact] : $contact;
                    $content = $this->smsService->getFinalContent($value,$numberGroupName,$request->input('message'));
                    $log     = new WhatsappLog();

                    foreach ($setWhatsAppGateway as $id => $credentials) {
                        
                        $rand      = rand($credentials['min_delay'] ,$credentials['max_delay']);
                        $addSecond = $i * $rand;
                        unset($setWhatsAppGateway[$id]);
                        
                        if(empty($setWhatsAppGateway)) {
                            
                            $setWhatsAppGateway = $allAvailableWaGateway;
                            $i++;
                        }
                        
                        break;
                    }
                    $log->whatsapp_id     = $id;
                    $log->user_id         = $userId;
                    $log->to              = $contact;
                    $log->initiated_time  = $setTimeInDelay;
                    $log->mode            = (boolean) $request->input("whatsapp_sending_mode") ? WhatsappLog::NODE : WhatsappLog::CLOUD_API;
                    $log->message         = $content;
                    $log->word_length     = $wordLength;
                    $log->status          = $request->input('schedule');
                    $log->file_info       = count($postData) > 0 ? $postData : null;
                    $log->schedule_status = $request->input('schedule');
                    $log->save();
                    
                    if (count($contactNewArray) == 1 && $request->input('schedule') == WhatsappLog::PENDING) { 

                        SendWhatsapp::sendNodeMessages($log, null);
                        
                    } elseif(count($contactNewArray) > 1) {
                        
                        ProcessWhatsapp::dispatch($log)->delay(Carbon::parse($setTimeInDelay)->addSeconds($addSecond));
                        $i++;
                    }
                }
            } else {
                
                foreach ($contactNewArray as $index_key => $number) {

                    $contact   = filterContactNumber($number);
                    $value     = array_key_exists($contact, $numberGroupName) ? $numberGroupName[$contact] : $contact;
                    $content   = $this->smsService->getFinalContent($value,$numberGroupName,$request->input('message'));
                    $rand      = rand($allAvailableWaGateway[array_key_first($allAvailableWaGateway)]["min_delay"], $allAvailableWaGateway[array_key_first($allAvailableWaGateway)]["max_delay"]);
                    $addSecond = $i * $rand;
                    
                    $log                  = new WhatsappLog();
                    $log->user_id         = $userId;
                    $log->whatsapp_id     = array_key_first($allAvailableWaGateway);
                    $log->to              = $contact;
                    $log->mode            = (boolean) $request->input("whatsapp_sending_mode") ? WhatsappLog::NODE : WhatsappLog::CLOUD_API;
                    $log->initiated_time  = $setTimeInDelay;
                    $log->message         = $content;
                    $log->word_length     = $wordLength;
                    $log->status          = $request->input('schedule');
                    $log->file_info       = count($postData) > 0 ? $postData : null;
                    $log->schedule_status = $request->input('schedule');
                    $log->save();

                    if (count($contactNewArray) == 1 && $request->input('schedule') == WhatsappLog::PENDING) { 

                        SendWhatsapp::sendNodeMessages($log, null);
                        
                    } elseif(count($contactNewArray) > 1) {
                        
                        ProcessWhatsapp::dispatch($log)->delay(Carbon::parse($setTimeInDelay)->addSeconds($addSecond));
                        $i++;
                    }
                }
            }
        }
    }

    /**
     * @param $search
     * @param $searchDate
     * @return Builder
     */
    public function searchWhatsappLog($search, $searchDate): Builder {

        $smsLogs = WhatsappLog::query();
        if (!empty($search)) {

            $smsLogs->whereHas('user',function ($q) use ($search) {
                $q->where('to', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if (!empty(request()->input('status'))) {
            $status = match (request()->input('status')){
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
            $firstDate = Carbon::createFromFormat('m/d/Y', trim($dateRange[0]))->startOfDay() ?? null;
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
     * @param WhatsappLog $whatsappLog
     * @param $gwException
     * @return void
     */
    public function addedCreditLog(WhatsappLog $whatsappLog ,$gwException): void {

        $user                          = User::find($whatsappLog->user_id);
        $whatsappLog->status           = WhatsappLog::FAILED;
        $whatsappLog->response_gateway = $gwException;
        $whatsappLog->save();
       
        if($whatsappLog->contact_id) {

            $status = "Fail";
        }

        if ($user) {

            $messages               = str_split($whatsappLog->message,$whatsappLog->word_length);
            $totalcredit            = count($messages);
            $user->whatsapp_credit += $totalcredit;
            $user->save();

            $creditInfo              = new WhatsappCreditLog();
            $creditInfo->user_id     = $whatsappLog->user_id;
            $creditInfo->type        = "+";
            $creditInfo->credit      = $totalcredit;
            $creditInfo->trx_number  = trxNumber();
            $creditInfo->post_credit =  $user->whatsapp_credit;
            $creditInfo->details     = $totalcredit." Credit Return ".$whatsappLog->to." is Falied";
            $creditInfo->save();
        }
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
                
                $i = 1;
                foreach ($ids as $id) {
                    
                    $statusValue = $request->input('status');
                    $item = $model::find($id);
                    if(!@$item?->campaign_id) { 

                        $gateway = WhatsappDevice::where("id", $item->gateway_id)->first();
                       
                        list($status, $message, $meta_data, $gateway) = $this->whatsappGatewayService->assignGateway($gateway->type == StatusEnum::TRUE->status() ? "cloud_api" : "without_cloud_api", $gateway->id, $item->meta_data);
                      
                        if($gateway) {
                            $this->send($gateway, count($meta_data), $item, $i);
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
     * @param CommunicationLog $log
     * @param $status
     * @param $errorMessage
     * @return void
     */
    public static function updateWhatsappLogAndCredit(CommunicationLog $log, $status, $errorMessage = null): void {
        
        $log->status           = $status;
        $log->response_message = !is_null($errorMessage) ? $errorMessage : null;
        $log->save();
        $user = User::find($log->user_id);

        if ($user && $status == CommunicationStatusEnum::FAIL->value) {

            $messages    = str_split($log->message["message_body"], site_settings("whatsapp_word_count"));
            $totalcredit = count($messages);
            CustomerService::addedCreditLog($user, $totalcredit, ServiceType::WHATSAPP->value);
        }
    }
}
