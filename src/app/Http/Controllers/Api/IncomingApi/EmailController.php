<?php

namespace App\Http\Controllers\Api\IncomingApi;






use App\Http\Resources\GetEmailLogResource;


use App\Models\CommunicationLog;
use App\Models\User;
use App\Models\Admin;
use App\Models\Gateway;
use App\Enums\StatusEnum;
use App\Enums\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Enums\CommunicationStatusEnum;
use App\Http\Resources\EmailLogResource;
use App\Http\Utility\Api\ApiJsonResponse;
use App\Rules\ValidGatewayIdentifier;
use App\Service\Admin\Core\CustomerService;


use Illuminate\Support\Facades\Validator;
use App\Service\Admin\Dispatch\EmailService;

use Illuminate\Validation\ValidationException;

class EmailController extends Controller
{
    public EmailService $emailService;
    public CustomerService $customerService;
    public function __construct(EmailService $emailService, CustomerService $customerService)
    {
        $this->emailService = $emailService;
        $this->customerService = $customerService;
    }
   
    /**
     * getEmailLog
     *
     * @param string|null $uid
     * 
     * @return JsonResponse
     */
    public function getEmailLog(?string $uid = null): JsonResponse {
        
        $emailLog = CommunicationLog::email()
                                        ->where('uid', $uid)
                                        ->first();
        
        if (!$emailLog) {

            return ApiJsonResponse::notFound(translate("Invalid Email Log uid"));
        }
        return ApiJsonResponse::success(translate('Successfully fetched Email from Logs'), new GetEmailLogResource($emailLog));
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(),[
                'contact'                      => 'required|array|min:1',
                'contact.*.subject'            => 'required|max:255',
                'contact.*.email'              => 'required|email|max:255',
                'contact.*.message'            => 'required',
                'contact.*.gateway_identifier' => 'nullable|exists:gateways,uid',
                'contact.*.sender_name'        => 'nullable|max:255',
                'contact.*.reply_to_email'     => 'nullable|email|max:255',
                'contact.*.schedule_at'        => 'nullable|date_format:Y-m-d H:i:s',
            ]);
            if ($validator->fails()) {
               
                return ApiJsonResponse::validationError($validator->errors());
            }
            $admin = Admin::where('api_key', $request->header('Api-key'))->first();
            $user = User::where('api_key', $request->header('Api-key'))->first();
            $allowed_access = $user ? (object) planAccess($user) : null;

            $data = $request->toArray();
            $emailLogs = collect();
            $gateway = [];
            $gatewayMessage = "Email default gateway is not set in the Admin Panel";
            $errors = [];
            foreach($data["contact"] as $entry) {
                
                if($user) {
                    $gatewayMessage = "Email default gateway is not set in the User Panel";
                    $allowed_access  = (object) planAccess($user);
                    $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::EMAIL->value);
                    if($has_daily_limit) { 

                        $remaining_email_credits = $user->email_credit;
                        $total_contact = 1;
                        if ($total_contact > $remaining_email_credits && $user->email_credit != -1) {

                            return ApiJsonResponse::error(translate("User ").$user->username.translate(" do not have sufficient credits for send email"));
                        } else {

                            $this->customerService->deductCreditLog($user, 1, ServiceType::EMAIL->value);
                        }   
                    } else {

                        return ApiJsonResponse::error(translate("User ").$user->username.translate(" has exceeded the daily credit limit"));
                    }

                    if($allowed_access->type == StatusEnum::FALSE->status()) {

                        if(array_key_exists("gateway_identifier", $entry)) {

                            $gateway = Gateway::where('uid', $entry['gateway_identifier'])->where('status', StatusEnum::TRUE->status())->where('user_id', $user->id)->first();
                            if(!$gateway) {
    
                                $gatewayMessage = "Choosen Gateway is inactive or does not exist";
                            }
                        } else {

                            $gateway =  Gateway::whereNotNull("mail_gateways")->where([
                                "user_id"    => $user->id,
                                "is_default" => StatusEnum::TRUE->status()
                            ])->first();
                        }
                        
                    } else {

                        if(array_key_exists("gateway_identifier", $entry)) {

                            $gateway = Gateway::where('uid', $entry['gateway_identifier'])->where('status', StatusEnum::TRUE->status())->whereNull('user_id')->first();
                            if(!$gateway) {
    
                                $gatewayMessage = "Choosen Gateway is inactive or does not exist";
                            }
                        } else {
                            $gateway = Gateway::whereNotNull("mail_gateways")->whereNull("user_id")->where([

                                "is_default" => StatusEnum::TRUE->status()
                            ])->first();
                        }
                    }
                    
                    if($gateway) {
                        
                        $meta_data = [
                            "gateway"          => $gateway->type,
                            "gateway_name"     => $gateway->name,
                            "gateway_id"       => $gateway->id,
                            "contact"          => $entry["email"],
                            "email_from_name"  => array_key_exists('sender_name', $entry) ? $entry["sender_name"] : $gateway->name,
                            "reply_to_address" => array_key_exists('reply_to_email', $entry) ? $entry["reply_to_email"] : $user->email,
                        ];
                        
                        $message = [
                            "subject" => $entry["subject"],
                            "message_body" => $entry["message"],
                        ];
                       
                        $log = $this->prepLog($entry, $gateway, $meta_data, $message, $user->id);
                        $log = $this->emailService->saveLog($log);
                        
                        if($gateway && !$log->campaign_id && $log->status != CommunicationStatusEnum::SCHEDULE->value) {
    
                            $this->emailService->send($gateway, $data["contact"], $log);
                        } 
                        $emailLogs->push(new EmailLogResource($log));
                    } else {
                        
                        $errors[] = [
                            "error" => [
                                "contact_data" => array_key_exists("email", $entry) ? $entry['email'] : 'unknown',
                                "message" => translate($gatewayMessage),
                            ]
                        ];
                    }
                } else {

                    if(array_key_exists("gateway_identifier", $entry)) {

                        $gateway = Gateway::where('uid', $entry['gateway_identifier'])->where('status', StatusEnum::TRUE->status())->first();
                        if(!$gateway) {

                            $gatewayMessage = "Choosen Gateway is inactive or does not exist";
                        }
                    } else {

                        $gateway = Gateway::whereNotNull("mail_gateways")->whereNull("user_id")->where([

                            "is_default" => StatusEnum::TRUE->status()
                        ])->first();
                    }
                    

                    if($gateway) {
                    
                        $meta_data = [
    
                            "gateway"      => $gateway->type,
                            "gateway_name" => $gateway->name,
                            "gateway_id"   => $gateway->id,
                            "contact"      => $entry["email"],
                            "email_from_name" => array_key_exists('sender_name', $entry) ? $entry["sender_name"] : $gateway->name,
                            "reply_to_address" => array_key_exists('reply_to_email', $entry) ? $entry["reply_to_email"] : $admin->email,
                        ];
                        $message = [
                            "subject" => $entry["subject"],
                            "message_body" => $entry["message"],
                        ];
    
                        $log = $this->prepLog($entry, $gateway, $meta_data, $message);
                        $log = $this->emailService->saveLog($log);
                        if($gateway && !$log->campaign_id && $log->status != CommunicationStatusEnum::SCHEDULE->value) {
    
                            $this->emailService->send($gateway, $data["contact"], $log);
                        } 
                        $emailLogs->push(new EmailLogResource($log));
                    } else {
                        
                        $errors[] = [
                            "error" => [
                                "contact_data" => array_key_exists("email", $entry) ? $entry['email'] : 'unknown',
                                "message" => translate($gatewayMessage),
                            ]
                        ];
                    }
                }
            } 
            return ApiJsonResponse::success(translate('New Email request sent, please check the Email history for final status'), array_merge($emailLogs->toArray(), $errors));

        } catch (\Exception $e) {
            return ApiJsonResponse::validationError($e->getMessage());
        }
    }

    public function sendWithQuery(Request $request): JsonResponse
    {
        try {

            $contacts = explode(',', $request->query('contacts', ''));
            $message = $request->query('message', '');
            $subject = $request->query('subject', '');

            if (empty($contacts) || !$message || !$subject) {
                return ApiJsonResponse::validationError(['contacts' => 'contacts, message & subject are required']);
            }

            collect($contacts)
                ?->lazy()
                ?->chunk(10)
                ->map(function($contact) {
                    if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                        return ApiJsonResponse::validationError(['email' => "Invalid email: $contact"]);
                    }
                });

            $admin          = Admin::where('api_key', $request->header('Api-key'))->first();
            $user           = User::where('api_key', $request->header('Api-key'))->first();
            $allowed_access = $user ? (object) planAccess($user) : null;

            $emailLogs = collect();
            $gateway = null;
            $gatewayMessage = "Email default gateway is not set in the Admin Panel";

            if ($user) {
                $gatewayMessage = "Email default gateway is not set in the User Panel";
                $allowed_access = (object) planAccess($user);
                $has_daily_limit = $this->customerService->canSpendCredits($user, $allowed_access, ServiceType::EMAIL->value);
                if ($has_daily_limit) {
                    $remaining_email_credits = $user->email_credit;
                    $total_contacts = count($contacts);
                    if ($total_contacts > $remaining_email_credits && $user->email_credit != -1) {
                        return ApiJsonResponse::error(translate("User ").$user->username.translate(" do not have sufficient credits for send email"));
                    }
                    $this->customerService->deductCreditLog($user, $total_contacts, ServiceType::EMAIL->value);
                } else {
                    return ApiJsonResponse::error(translate("User ").$user->username.translate(" has exceeded the daily credit limit"));
                }

                if ($allowed_access->type == StatusEnum::FALSE->status()) {
                    $gateway = Gateway::whereNotNull("mail_gateways")->where([
                        "user_id"    => $user->id,
                        "is_default" => StatusEnum::TRUE->status()
                    ])->first();
                } else {
                    $gateway = Gateway::whereNotNull("mail_gateways")->whereNull("user_id")->where([
                        "is_default" => StatusEnum::TRUE->status()
                    ])->first();
                }
            } else {
                $gateway = Gateway::whereNotNull("mail_gateways")->whereNull("user_id")->where([
                    "is_default" => StatusEnum::TRUE->status()
                ])->first();
            }

            if (!$gateway) {
                return ApiJsonResponse::error(translate($gatewayMessage), [
                    'contact_data' => implode(", ", $contacts)
                ]);
            }

            foreach ($contacts as $contact) {
                $meta_data = [
                    "gateway"          => $gateway->type,
                    "gateway_name"     => $gateway->name,
                    "gateway_id"       => $gateway->id,
                    "contact"          => $contact,
                    "email_from_name"  => $gateway->name,
                    "reply_to_address" => $user ? $user->email : $admin->email,
                ];
                $message_data = [
                    "subject"      => $subject,
                    "message_body" => $message,
                ];
                $log = $this->prepLog(["email" => $contact], $gateway, $meta_data, $message_data, $user ? $user->id : null);
                $log = $this->emailService->saveLog($log);
                if ($gateway && !$log->campaign_id && $log->status != CommunicationStatusEnum::SCHEDULE->value) {
                    $this->emailService->send($gateway, [["email" => $contact]], $log);
                }
                $emailLogs->push(new EmailLogResource($log));
            }

            return ApiJsonResponse::success(translate('New Email request sent, please check the Email history for final status'), $emailLogs->toArray());
        } catch (\Exception $e) {
            return ApiJsonResponse::validationError(['exception' => getEnvironmentMessage($e->getMessage())]);
        }
    }

    private function prepLog($data, $gateway, $meta_data, $message, $user_id = null) {

        $log = [
            'user_id'    => $user_id,
            'type'       => ServiceType::EMAIL->value,
            'gateway_id' => $gateway->id,
            'message'    => $message,
            'meta_data'  => $meta_data,
            'schedule_at' => array_key_exists('schedule_at', $data) ? $data['schedule_at'] : null
        ];
        return $log;
    }

}