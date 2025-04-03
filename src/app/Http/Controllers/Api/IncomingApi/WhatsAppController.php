<?php

namespace App\Http\Controllers\Api\IncomingApi;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\GetWhatsAppLogResource;
use App\Http\Resources\WhatsAppLogResource;
use App\Http\Utility\Api\ApiJsonResponse;
use App\Jobs\ProcessWhatsapp;
use App\Models\Admin;
use App\Models\CommunicationLog;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\WhatsappDevice;
use App\Models\WhatsappLog;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Dispatch\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class WhatsAppController extends Controller
{
    public WhatsAppService $whatsappService;
    public CustomerService $customerService;
    public function __construct(CustomerService $customerService, WhatsAppService $whatsappService)
    {
        $this->customerService = $customerService;
        $this->whatsappService = $whatsappService;
    }

    /**
     * getWhatsAppLog
     *
     * @param string|null $uid
     * 
     * @return JsonResponse
     */
    public function getWhatsAppLog(?string $uid = null): JsonResponse {

        $whatsLog = CommunicationLog::whatsapp()->where('uid', $uid)->first();
        
        if (!$whatsLog) {

            return ApiJsonResponse::notFound(translate("Invalid WhatsApp Log uid"));
        }
        return ApiJsonResponse::success(translate('Successfully fetched WhatsApp from Logs'), new GetWhatsAppLogResource($whatsLog));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(),[
            
                'contact'                => 'required|array|min:1',
                'contact.*.number'       => 'required|max:255',
                'contact.*.message'      => 'required',
                'contact.*.media'        => 'nullable|in:image,audio,video,document',
                'contact.*.url'          => 'nullable|url',
                'contact.*.schedule_at'  => 'nullable|date_format:Y-m-d H:i:s',
                'contact.*.session_name' => 'nullable|exists:wa_device,name',
            ]);
            if ($validator->fails()) {
                   
                return ApiJsonResponse::validationError($validator->errors());
            }
    
            $addSecond       = 50; 
            $i               = 1;
            $user            = User::where('api_key', $request->header('Api-key'))->first();
            $admin           = Admin::where('api_key', $request->header('Api-key'))->first();
            $setTimeInDelay  = Carbon::now();
            $whatsAppHistory = collect(); 
            $errors = [];
            $specifiedGateway = null;
            $whatsappGateway = null;
            $whatsappGateway = $user ? WhatsappDevice::where("type", StatusEnum::FALSE->status())->where('user_id', $user->id)->where('status', 'connected')->get() : WhatsappDevice::where("type", StatusEnum::FALSE->status())->where('admin_id', $admin->id)->where('status', 'connected')->get();
            $gatewayMessage = "Choosen device is not connected or does not exist";
            if (count($whatsappGateway) == 0) {
    
                return ApiJsonResponse::notFound(translate("WhatsApp Node device is not available"));
            } else {
                
                foreach ($request->input('contact') as $key => $value) {
                    
                    if(array_key_exists("session_name", $value)) {
    
                        $specifiedGateway = WhatsappDevice::where("name", $value['session_name'])->where("type", StatusEnum::FALSE->status())->where('status', 'connected')->get();
                       
                    } else {
                        $specifiedGateway = null;
                    }
                    
                    $specifiedGateway = $specifiedGateway ? $specifiedGateway : $whatsappGateway;
                    
                    if($specifiedGateway->first()) {
                        $whatsappGateway    = $specifiedGateway;
                        $setWhatsAppGateway = $whatsappGateway->pluck('id')->toArray();
                       
                        if ($user) {
            
                            $allowed_access = (object) planAccess($user);
                            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::WHATSAPP->value);
                            if($has_daily_limit) { 
                                $messages    = str_split($value['message'] ?: $value['message'], site_settings("whatsapp_word_count"));
                                $totalCredit = count($messages);
            
                                if ($totalCredit > $user->whatsapp_credit && $user->whatsapp_credit != -1) {
            
                                    return ApiJsonResponse::error(translate("User ").$user->username.translate(" do not have sufficient credits for send whatsapp message"));
                                }
            
                                $this->customerService->deductCreditLog($user, $totalCredit, ServiceType::WHATSAPP->value);
                                $postData = [
                                    'type'     => Arr::get($value,'media'),
                                    'url_file' => Arr::get($value,'url'),
                                    'name'     => Arr::get($value,'url')
                                ];
                            
                                foreach ($setWhatsAppGateway as $key => $appGateway) {
                                
                                    $gateway = $whatsappGateway->where('id',$appGateway)->first();
                                    if($gateway) {

                                        $rand      = rand($gateway->credentials['min_delay'] ,$gateway->credentials['max_delay']);
                                        $addSecond = $i * $rand;
                                        unset($setWhatsAppGateway[$key]);
                                        if (empty($setWhatsAppGateway)) {
                                            
                                            $setWhatsAppGateway = $whatsappGateway->pluck('id')->toArray();
                                            $i++;
                                        }
                                    }
                                    break;
                                }
                                
                                $meta_data = [
        
                                    'contact' => Arr::get($value, 'number'),
                                    'gateway' => "WhatsApp Node Module",
                                    'gateway_name' => $whatsappGateway->first()->name,
                                ];
                                $message = [
                                    'message_body' => str_replace('{{name}}',Arr::get($value, 'number'),offensiveMsgBlock(Arr::get($value, 'message')))
                                ];
                                foreach ($postData as $k => $v) {
                    
                                    if($v == null) {
                    
                                        $postData = null;
                                    }
                                }
                                $log = CommunicationLog::create([
                                    'user_id'     => $user ? $user->id : null,
                                    'type'        => ServiceType::WHATSAPP->value,
                                    'gateway_id'  => $whatsappGateway->first()->id,
                                    'meta_data'   => $meta_data,
                                    'message'     => $message,
                                    'status'      => array_key_exists('schedule_at', $value) ? CommunicationStatusEnum::SCHEDULE->value : CommunicationStatusEnum::PENDING->value,
                                    'file_info'   => $postData,
                                    'schedule_at' => array_key_exists('schedule_at', $value) ? $value['schedule_at'] : null
                                ]);
                                $whatsAppHistory->push(new WhatsAppLogResource($log));
                                if ($log->status == WhatsappLog::PENDING) { 
                
                                    ProcessWhatsapp::dispatch($log)->delay(Carbon::parse($setTimeInDelay)->addSeconds($addSecond));
                                }
                                
                            } else {
            
                                return ApiJsonResponse::error(translate("User ").$user->name.translate(" has exceeded the daily credit limit"));
                            }
                            
                        } else {
            
                            $postData = [
                                'type'     => Arr::get($value,'media'),
                                'url_file' => Arr::get($value,'url'),
                                'name'     => Arr::get($value,'url')
                            ];
                        
                            foreach ($setWhatsAppGateway as $key => $appGateway) {
                                
                                $gateway   = $whatsappGateway->where('id',$appGateway)->first();
                                
                                if($gateway) {
                                    
                                    $rand      = rand($gateway->credentials['min_delay'], $gateway->credentials['max_delay']);
                                    $addSecond = $i * $rand;
                                
                                    unset($setWhatsAppGateway[$key]);
                                
                                    if (empty($setWhatsAppGateway)) {
                                        
                                        $setWhatsAppGateway = $whatsappGateway->pluck('id')->toArray();
                                    
                                        $i++;
                                    }
                                    break;
                                }
                            }
                            $meta_data = [
                                'contact' => Arr::get($value, 'number'),
                                'gateway' => "WhatsApp Node Module",
                                'gateway_name' => $whatsappGateway->first()->name,
                            ];
                            $message = [
                                'message_body' => str_replace('{{name}}',Arr::get($value, 'number'),offensiveMsgBlock(Arr::get($value, 'message')))
                            ];
                            foreach ($postData as $k => $v) {
                
                                if($v == null) {
                
                                    $postData = null;
                                }
                            }

                            $log = CommunicationLog::create([
                                'user_id'     => $user ? $user->id : null,
                                'type'        => ServiceType::WHATSAPP->value,
                                'gateway_id'  => $whatsappGateway->first()->id,
                                'meta_data'   => $meta_data,
                                'message'     => $message,
                                'status'      => array_key_exists('schedule_at', $value) ? CommunicationStatusEnum::SCHEDULE->value : CommunicationStatusEnum::PENDING->value,
                                'file_info'   => $postData,
                                'schedule_at' => array_key_exists('schedule_at', $value) ? $value['schedule_at'] : null
                            ]);
                            $whatsAppHistory->push(new WhatsAppLogResource($log));
                            if ($log->status == WhatsappLog::PENDING) { 
            
                                ProcessWhatsapp::dispatch($log)->delay(Carbon::parse($setTimeInDelay)->addSeconds($addSecond));
                            }
                        }
                    } else {
                        $errors[] = [
                            "error" => [
                                "contact_data" => array_key_exists("number", $value) ? $value['number'] : 'unknown',
                                "message" => translate($gatewayMessage),
                            ]
                        ];
                    }
                }
                return ApiJsonResponse::success(translate('New WhatsApp request sent, please check the WhatsApp history for final status'), array_merge($whatsAppHistory->toArray(), $errors));
            }
        } catch (\Exception $e) {
            return ApiJsonResponse::validationError($e->getMessage());
        }
    } 

    public function sendWithQuery(Request $request)
    {
        try {
            $contacts = $this->validateAndExtractContacts($request);
            $message = $this->validateAndExtractMessage($request);

            $authData = $this->authenticateRequest($request);
            $admin = $authData['admin'];
            $user = $authData['user'];
            $allowed_access = $authData['allowed_access'];

            $whatsAppHistory = collect();
            $gatewayData = $this->selectGateway($admin, $user);
            $whatsappGateway = $gatewayData['whatsappGateway'];
            $gatewayMessage = $gatewayData['gatewayMessage'];

            if (count($whatsappGateway) == 0) {
                return ApiJsonResponse::notFound(translate("WhatsApp Node device is not available"));
            }

            if ($user) {
                $creditCheck = $this->checkAndDeductCredits($user, $allowed_access, $contacts, $message);
                if (!$creditCheck['success']) {
                    return ApiJsonResponse::error($creditCheck['message']);
                }
            }

            $addSecond = 50;
            $i = 1;
            $setTimeInDelay = Carbon::now();
            $setWhatsAppGateway = $whatsappGateway->pluck('id')->toArray();

            foreach ($contacts as $contact) {
                $gateway = $this->assignGateway($whatsappGateway, $setWhatsAppGateway, $i);
                if (!$gateway) {
                    return ApiJsonResponse::error(translate($gatewayMessage), [
                        'contact_data' => implode(", ", $contacts)
                    ]);
                }

                $rand = rand($gateway->credentials['min_delay'], $gateway->credentials['max_delay']);
                $addSecond = $i * $rand;
                $setWhatsAppGateway = $this->updateGatewayList($setWhatsAppGateway, $gateway, $whatsappGateway, $i);

                $meta_data = [
                    'contact' => $contact,
                    'gateway' => "WhatsApp Node Module",
                    'gateway_name' => $gateway->name,
                ];
                $message_data = [
                    'message_body' => str_replace('{{name}}', $contact, offensiveMsgBlock($message))
                ];
                $log = CommunicationLog::create([
                    'user_id'     => $user ? $user->id : ($admin ? $admin->id : null),
                    'type'        => ServiceType::WHATSAPP->value,
                    'gateway_id'  => $gateway->id,
                    'meta_data'   => $meta_data,
                    'message'     => $message_data,
                    'status'      => CommunicationStatusEnum::PENDING->value,
                    'file_info'   => null,
                    'schedule_at' => null
                ]);
                $whatsAppHistory->push(new WhatsAppLogResource($log));
                if ($log->status == WhatsappLog::PENDING) {
                    ProcessWhatsapp::dispatch($log)->delay(Carbon::parse($setTimeInDelay)->addSeconds($addSecond));
                }
            }

            return ApiJsonResponse::success(translate('New WhatsApp request sent, please check the WhatsApp history for final status'), $whatsAppHistory->toArray());
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

    private function authenticateRequest(Request $request)
    {
        $admin = Admin::where('api_key', $request->header('Api-key'))->first();
        $user = User::where('api_key', $request->header('Api-key'))->first();
        $allowed_access = $user ? (object) planAccess($user) : null;
        return [
            'admin' => $admin,
            'user' => $user,
            'allowed_access' => $allowed_access
        ];
    }

    private function checkAndDeductCredits($user, $allowed_access, $contacts, $message)
    {
        if ($user) {
            $allowed_access = (object) planAccess($user);
            $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::WHATSAPP->value);
            if ($has_daily_limit) {
                $messages = str_split($message, site_settings("whatsapp_word_count"));
                $totalCredit = count($messages) * count($contacts);
                if ($totalCredit > $user->whatsapp_credit && $user->whatsapp_credit != -1) {
                    return [
                        'success' => false,
                        'message' => translate("User ").$user->username.translate(" do not have sufficient credits for send whatsapp message")
                    ];
                }
                $this->customerService->deductCreditLog($user, $totalCredit, ServiceType::WHATSAPP->value);
            } else {
                return [
                    'success' => false,
                    'message' => translate("User ").$user->username.translate(" has exceeded the daily credit limit")
                ];
            }
        }
        return ['success' => true];
    }

    private function selectGateway($admin, $user)
    {
        $whatsappGateway = $user ? 
            WhatsappDevice::where("type", StatusEnum::FALSE->status())->where('user_id', $user->id)->where('status', 'connected')->get() : 
            WhatsappDevice::where("type", StatusEnum::FALSE->status())->where('admin_id', $admin->id)->where('status', 'connected')->get();
        $gatewayMessage = "Choosen device is not connected or does not exist";
        return [
            'whatsappGateway' => $whatsappGateway,
            'gatewayMessage' => $gatewayMessage
        ];
    }

    private function assignGateway($whatsappGateway, $setWhatsAppGateway, $i)
    {
        foreach ($setWhatsAppGateway as $key => $appGateway) {
            $gateway = $whatsappGateway->where('id', $appGateway)->first();
            if ($gateway) {
                return $gateway;
            }
        }
        return null;
    }

    private function updateGatewayList($setWhatsAppGateway, $gateway, $whatsappGateway, &$i)
    {
        $key = array_search($gateway->id, $setWhatsAppGateway);
        unset($setWhatsAppGateway[$key]);
        if (empty($setWhatsAppGateway)) {
            $setWhatsAppGateway = $whatsappGateway->pluck('id')->toArray();
            $i++;
        }
        return $setWhatsAppGateway;
    }
}