<?php

namespace App\Http\Controllers\Api\IncomingApi;

use App\Enums\AndroidApiSimEnum;
use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\GetSmsLogResource;
use App\Http\Resources\SmsLogResource;
use App\Models\CommunicationLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Utility\Api\ApiJsonResponse;
use App\Models\Admin;
use App\Models\AndroidApi;
use App\Models\Gateway;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Dispatch\SmsService;
use App\Service\Admin\Gateway\SmsGatewayService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public SmsService $smsService;
    public CustomerService $customerService;
    public SmsGatewayService $smsGatewayService;


    public function __construct(SmsService $smsService, CustomerService $customerService, SmsGatewayService $smsGatewayService) {

        $this->smsService      = $smsService;
        $this->customerService = $customerService;
        $this->smsGatewayService = $smsGatewayService;
    }

    /**
     * getSmsLog
     *
     * @param string|null $uid
     * 
     * @return JsonResponse
     */
    public function getSmsLog(?string $uid = null): JsonResponse {

        $smsLog = CommunicationLog::sms()->where('uid', $uid)->first();
        
        if (!$smsLog) {

            return ApiJsonResponse::notFound(translate("Invalid SMS Log uid"));
        }
        return ApiJsonResponse::success(translate('Successfully fetched SMS from Logs'), new GetSmsLogResource($smsLog));
    }

    public function store(Request $request) {
        
        try {
            $validator = Validator::make($request->all(),[
                'contact'               => 'required|array|min:1',
                'contact.*.number'      => 'required|max:255',
                'contact.*.body'        => 'required',
                'contact.*.sms_type'    => 'required|in:plain,unicode',
                'contact.*.schedule_at' => 'nullable|date_format:Y-m-d H:i:s',
                'contact.*.gateway_identifier' => 'nullable',
            ]);
            if ($validator->fails()) {
               
                return ApiJsonResponse::validationError($validator->errors());
            }
            
            $user = User::where('api_key', $request->header('Api-key'))->first();
            $allowed_access = $user ? (object) planAccess($user) : null;
            $data = $request->toArray();
            $smsLogs = collect();
            $gateway = [];
            $gatewayMessage = site_settings("api_sms_method") == StatusEnum::FALSE->status() ? translate("SMS default gateway is not set in the Admin Panel") : translate("There are no active sim connected with the application");
            $errors = [];
            foreach($data["contact"] as $entry) {
                
                $message = [
                    "message_body" => $entry["body"],
                ];

                if($user) {
                    $allowed_access = (object) planAccess($user);
                    $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value);
                    if($has_daily_limit) {

                        $remaining_sms_credits = $user->sms_credit;
                        $word_length   = $entry["sms_type"] == "unicode" ? site_settings("sms_word_unicode_count") : site_settings("sms_word_count");
                        $total_message = count(str_split($message["message_body"],$word_length));
                        $total_contact = count($data["contact"]);
                        $total_credits = $total_contact * $total_message;
                        if ($total_credits > $remaining_sms_credits && $user->sms_credit != -1) {

                            return ApiJsonResponse::error(translate("User ").$user->username.translate(" do not have sufficient credits for send message"));
                            
                        } else {

                            $this->customerService->deductCreditLog($user, 1, ServiceType::SMS->value);

                            if($allowed_access->type == StatusEnum::FALSE->status()) {

                                if(array_key_exists("gateway_identifier", $entry)) {

                                    $gateway = $this->smsGatewayService->apiAssignGateway($entry['gateway_identifier']);
                                    if(!$gateway) {
    
                                        $gatewayMessage = "Choosen Gateway is inactive or does not exist";
                                    }
                                } else {

                                    $gateway = $user->api_sms_method == StatusEnum::FALSE->status() ? 
                                    Gateway::whereNotNull("sms_gateways")->where([
                                        "user_id"    => $user->id,
                                        "is_default" => StatusEnum::TRUE->status()
                                    ])->first() : AndroidApi::where("user_id", $user->id)->inRandomOrder()->first()->simInfo()->where("status", AndroidApiSimEnum::ACTIVE)->first();
                                }
                        
                                
                            } else {

                                if(array_key_exists("gateway_identifier", $entry)) {

                                    $gateway = $this->smsGatewayService->apiAssignGateway($entry['gateway_identifier']);
                                    if(!$gateway) {
    
                                        $gatewayMessage = "Choosen Gateway is inactive or does not exist";
                                    }
                                } else {

                                    $gateway = site_settings("api_sms_method") == StatusEnum::FALSE->status() ? 
                                    Gateway::whereNotNull("sms_gateways")->where([
                                        "is_default" => StatusEnum::TRUE->status()
                                    ])->first() : AndroidApi::whereNull("user_id")->inRandomOrder()->first()?->simInfo()->where("status", AndroidApiSimEnum::ACTIVE)->first();
                                }
                            }
                            if($gateway) {
                            
                                $meta_data = [
            
                                    "gateway"      => $user->api_sms_method == StatusEnum::FALSE->status() ? $gateway->type : $gateway->androidGateway()->where("id", $gateway->android_gateway_id)->first()->name,
                                    "gateway_name" => $user->api_sms_method == StatusEnum::FALSE->status() ? $gateway->name : $gateway->sim_number,
                                    "contact"      => $entry["number"],
                                    "sms_type"     => $entry["sms_type"],
                                ];
                                $log = $this->prepLog($entry, $gateway, $meta_data, $message, $user->id);
                                $log = $this->smsService->saveLog($log);
                                if($gateway && !$log->campaign_id && $log->status != CommunicationStatusEnum::SCHEDULE->value) {
            
                                    $this->smsService->send($gateway, $data["contact"], $log);
                                } 
                                $smsLogs->push(new SmsLogResource($log));
                            } else {
                                $gatewayMessage = $user->api_sms_method == StatusEnum::FALSE->status() ? translate("SMS default gateway is not set in your panel") : translate("There are no active sim connected with the application");
                                $errors[] = [
                                    "error" => [
                                        "contact_data" => array_key_exists("number", $entry) ? $entry['number'] : 'unknown',
                                        "message" => translate($gatewayMessage),
                                    ]
                                ];
                            }
                        }
                    } else {

                        return ApiJsonResponse::error(translate("User ").$user->username.translate(" has exceeded the daily credit limit"));
                    }

                } else {

                    if(array_key_exists("gateway_identifier", $entry)) {

                        $gateway = $this->smsGatewayService->apiAssignGateway($entry['gateway_identifier']);
                        if(!$gateway) {

                            $gatewayMessage = "Choosen Gateway is inactive or does not exist";
                        }
                    } else {

                        $gateway = site_settings("api_sms_method") == StatusEnum::FALSE->status() ? 
                        Gateway::whereNotNull("sms_gateways")->where([
                            "is_default" => ServiceType::SMS->value
                        ])->first() : AndroidApi::whereNull("user_id")->inRandomOrder()->first()?->simInfo()->where("status", AndroidApiSimEnum::ACTIVE)->first();
                    }
                    
                    if($gateway) {
                    
                        $meta_data = [
    
                            "gateway"      => site_settings("api_sms_method") == StatusEnum::FALSE->status() ? $gateway->type : $gateway->androidGateway()->where("id", $gateway->android_gateway_id)->first()->name,
                            "gateway_name" => site_settings("api_sms_method") == StatusEnum::FALSE->status() ? $gateway->name : $gateway->sim_number,
                            "contact"      => $entry["number"],
                            "sms_type"     => $entry["sms_type"],
                        ];
                        $message = [
                            "message_body" => $entry["body"],
                        ];
    
                        $log = $this->prepLog($entry, $gateway, $meta_data, $message);
                        $log = $this->smsService->saveLog($log);
                        if($gateway && !$log->campaign_id && $log->status != CommunicationStatusEnum::SCHEDULE->value) {
    
                            $this->smsService->send($gateway, $data["contact"], $log);
                        } 
                        $smsLogs->push(new SmsLogResource($log));
                    }  else {
                    
                        $errors[] = [
                            "error" => [
                                "contact_data" => array_key_exists("number", $entry) ? $entry['number'] : 'unknown',
                                "message" => translate($gatewayMessage),
                            ]
                        ];
                    } 
                }
            } 
            return ApiJsonResponse::success(translate('New SMS request sent, please check the SMS history for final status'), array_merge($smsLogs->toArray(), $errors));

        } catch (\Exception $e) {

            return ApiJsonResponse::validationError($e->getMessage());
        }
    }

    public function sendWithQuery(Request $request)
    {
        try {
            $contacts = $this->validateAndExtractContacts($request);
            $message = $this->validateAndExtractMessage($request);
            $sms_type = $this->validateAndExtractSmsType($request);

            $authData = $this->authenticateRequest($request);
            $admin = $authData['admin'];
            $user = $authData['user'];
            $allowed_access = $authData['allowed_access'];

            $smsLogs = collect();
            $gatewayData = $this->selectGateway($admin, $user, $allowed_access);
            $gateway = $gatewayData['gateway'];
            $gatewayMessage = $gatewayData['gatewayMessage'];

            if ($user) {
                $creditCheck = $this->checkAndDeductCredits($admin, $user, $allowed_access, $contacts, $message, $sms_type);
                if (!$creditCheck['success']) {
                    return ApiJsonResponse::error($creditCheck['message']);
                }
            }

            if (!$gateway) {
                return ApiJsonResponse::error(translate($gatewayMessage), [
                    'contact_data' => implode(", ", $contacts)
                ]);
            }

            foreach ($contacts as $contact) {
                $meta_data = [
                    "gateway"      => ($admin && site_settings("api_sms_method") == StatusEnum::FALSE->status()) || ($user && $user->api_sms_method == StatusEnum::FALSE->status()) ? $gateway->type : $gateway->androidGateway()->where("id", $gateway->android_gateway_id)->first()->name,
                    "gateway_name" => ($admin && site_settings("api_sms_method") == StatusEnum::FALSE->status()) || ($user && $user->api_sms_method == StatusEnum::FALSE->status()) ? $gateway->name : $gateway->sim_number,
                    "contact"      => $contact,
                    "sms_type"     => $sms_type,
                ];
                $message_data = [
                    "message_body" => $message,
                ];
                $log = $this->prepLog(["number" => $contact, "sms_type" => $sms_type], $gateway, $meta_data, $message_data, $user ? $user->id : ($admin ? $admin->id : null));
                $log = $this->smsService->saveLog($log);
                if ($gateway && !$log->campaign_id && $log->status != CommunicationStatusEnum::SCHEDULE->value) {
                    $this->smsService->send($gateway, [["number" => $contact]], $log);
                }
                $smsLogs->push(new SmsLogResource($log));
            }

            return ApiJsonResponse::success(translate('New SMS request sent, please check the SMS history for final status'), $smsLogs->toArray());
        } catch (\Exception $e) {
            return ApiJsonResponse::validationError(['exception' => $e->getMessage()]);
        }
    }

    private function validateAndExtractContacts(Request $request)
    {
        $contacts = explode(',', $request->query('contacts', ''));
        if (empty($contacts)) {
            throw new \Exception('Contacts are required');
        }
        return $contacts;
    }

    private function validateAndExtractMessage(Request $request)
    {
        $message = $request->query('message', '');
        if (!$message) {
            throw new \Exception('Message is required');
        }
        return $message;
    }

    private function validateAndExtractSmsType(Request $request)
    {
        $sms_type = $request->query('sms_type', 'plain');
        if (!in_array($sms_type, ['plain', 'unicode'])) {
            throw new \Exception('SMS type must be plain or unicode');
        }
        return $sms_type;
    }

    private function authenticateRequest(Request $request)
    {
        $admin          = Admin::where('api_key', $request->header('Api-key'))->first();
        $user           = User::where('api_key', $request->header('Api-key'))->first();
        $allowed_access = $user ? (object) planAccess($user) : null;
        return [
            'admin' => $admin,
            'user' => $user,
            'allowed_access' => $allowed_access
        ];
    }

    private function checkAndDeductCredits($admin, $user, $allowed_access, $contacts, $message, $sms_type)
    {
        if ($user) {
            $allowed_access = (object) planAccess($user);
            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::SMS->value);
            if ($has_daily_limit) {
                $remaining_sms_credits = $user->sms_credit;
                $word_length = $sms_type == "unicode" ? site_settings("sms_word_unicode_count") : site_settings("sms_word_count");
                $total_message = count(str_split($message, $word_length));
                $total_contacts = count($contacts);
                $total_credits = $total_contacts * $total_message;
                if ($total_credits > $remaining_sms_credits && $user->sms_credit != -1) {
                    return [
                        'success' => false,
                        'message' => translate("User ").$user->username.translate(" do not have sufficient credits for send message")
                    ];
                }
                $this->customerService->deductCreditLog($user, $total_credits, ServiceType::SMS->value);
            } else {
                return [
                    'success' => false,
                    'message' => translate("User ").$user->username.translate(" has exceeded the daily credit limit")
                ];
            }
        }
        return ['success' => true];
    }

    private function selectGateway($admin, $user, $allowed_access)
    {
        $gateway = null;
        $gatewayMessage = site_settings("api_sms_method") == StatusEnum::FALSE->status() ? translate("SMS default gateway is not set in the Admin Panel") : translate("There are no active sim connected with the application");

        if ($user) {
            $gatewayMessage = $user->api_sms_method == StatusEnum::FALSE->status() ? translate("SMS default gateway is not set in your panel") : translate("There are no active sim connected with the application");
            if ($allowed_access->type == StatusEnum::FALSE->status()) {
                $gateway = $user->api_sms_method == StatusEnum::FALSE->status() ?
                    Gateway::whereNotNull("sms_gateways")->where([
                        "user_id"    => $user->id,
                        "is_default" => StatusEnum::TRUE->status()
                    ])->first() :
                    AndroidApi::where("user_id", $user->id)->inRandomOrder()->first()->simInfo()->where("status", AndroidApiSimEnum::ACTIVE)->first();
            } else {
                $gateway = site_settings("api_sms_method") == StatusEnum::FALSE->status() ?
                    Gateway::whereNotNull("sms_gateways")->where([
                        "is_default" => StatusEnum::TRUE->status()
                    ])->first() :
                    AndroidApi::whereNull("user_id")->inRandomOrder()->first()?->simInfo()->where("status", AndroidApiSimEnum::ACTIVE)->first();
            }
        } elseif ($admin) {
            $gateway = site_settings("api_sms_method") == StatusEnum::FALSE->status() ?
                Gateway::whereNotNull("sms_gateways")->where([
                    "is_default" => ServiceType::SMS->value
                ])->first() :
                AndroidApi::whereNull("user_id")->inRandomOrder()->first()?->simInfo()->where("status", AndroidApiSimEnum::ACTIVE)->first();
        }

        return [
            'gateway' => $gateway,
            'gatewayMessage' => $gatewayMessage
        ];
    }

    private function prepLog($data, $gateway, $meta_data, $message, $user_id = null) {

        $log = [
            'user_id'    => $user_id,
            'type'       => ServiceType::SMS->value,
            'gateway_id' => $gateway->android_gateway_id ? null : $gateway->id,
            'android_gateway_sim_id' => $gateway->android_gateway_id ? $gateway->id : null,
            'message'    => $message,
            'meta_data'  => $meta_data,
            'schedule_at' => array_key_exists('schedule_at', $data) ? $data['schedule_at'] : null
        ];
        return $log;
    }
}