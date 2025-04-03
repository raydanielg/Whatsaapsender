<?php
namespace App\Http\Utility;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Models\CampaignContact;
use App\Models\CommunicationLog;
use App\Models\Template;
use App\Models\User;
use App\Models\WhatsappCreditLog;
use App\Models\WhatsappDevice;
use App\Service\Admin\Core\CustomerService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class SendWhatsapp
{
    /**
     * @param $whatsappLog
     * @return void
     */
    public static function sendNodeMessages(CommunicationLog $whatsappLog, $wordLength = null) {

        $body = [];
            
        if(!is_null($whatsappLog->file_info)) {
            
            $url  = Arr::get($whatsappLog->file_info, 'url_file', null);
            $type = Arr::get($whatsappLog->file_info, 'type', null);
            $name = Arr::get($whatsappLog->file_info, 'name', null);

            if(!filter_var($url, FILTER_VALIDATE_URL)) {

                $url = url($url);
            }

            if($type == "image" ) {

                $body = [
                    'image'    => [
                        'url'=>$url
                    ],
                    'mimetype' => 'image/jpeg',
                    'caption'  => $whatsappLog->message['message_body'],
                ];
            }

            elseif($type == "audio" ) {

                $body = [
                    'audio' => [
                        'url' => $url
                    ],
                    'caption' => $whatsappLog->message['message_body'],
                ];
            }

            elseif($type == "video" ) {

                $body = [
                    'video' => [

                        'url' => $url
                    ],
                    'caption' => $whatsappLog->message['message_body'],
                ];
            }

            elseif($type == "document" ) {

                $body = [
                    'document' => [
                        'url' => $url
                    ],
                    'mimetype' => 'application/pdf',
                    'fileName' => $name,
                    'caption'  => $whatsappLog->message['message_body'],
                ];
            }
        } else {

            $body['text'] = $whatsappLog->message['message_body'];
        }
        
        //send api
        $response = null;
        try {

            $apiURL    = env('WP_SERVER_URL').'/message/send?id='.$whatsappLog->whatsappGateway->name;
            
            $postInput = [
                'receiver' => trim($whatsappLog->meta_data['contact']),
                'message'  => $body
            ];
            
            $headers = [
                'Content-Type' => 'application/json',
            ];
            $response = Http::withoutVerifying()->withHeaders($headers)->post($apiURL, $postInput);
            if ($response) {

                $res = json_decode($response->getBody(), true);

                if($res['success']) {
                    $meta_data = $whatsappLog->meta_data;
                    $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
                    $whatsappLog->meta_data = $meta_data;
                    $whatsappLog->status = CommunicationStatusEnum::DELIVERED->value;
                    $whatsappLog->save();
                } else {

                    self::processFailed($whatsappLog);
                }
            } else {
                self::processFailed($whatsappLog);
            }
        } catch(\Exception $exception) {
            
            self::processFailed($whatsappLog, $exception->getMessage());
            \Log::error("WhatsApp dispatch fail: " . $exception->getMessage());
        }
    }

   
    /**
     * @param $whatsappLog
     * @return void
     */
    public static function sendCloudApiMessages(CommunicationLog $whatsappLog, $wordLength = null) {

        try {
            $cloud_api           = WhatsappDevice::find($whatsappLog->gateway_id);
            $template            = Template::find($whatsappLog->whatsapp_template_id);
            $default_crendetials = (object) config("setting.whatsapp_business_credentials.default");
            $gateway_credentials = (object) $cloud_api->credentials;
            $url                 = "https://graph.facebook.com/$default_crendetials->version/$gateway_credentials->phone_number_id/messages";
            
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer $gateway_credentials->user_access_token",
                'Cookie'        => 'ps_l=0; ps_n=0',
            ];
            
            if($whatsappLog->message['message_body'] == []) {

                $data = [
                
                    'messaging_product' => 'whatsapp',
                    'to'                => $whatsappLog->meta_data['contact'],
                    'type'              => 'template',
                    "template" => [
                        "name" => $template->name,
                        "language" => [
                            "code" => $template->template_data['language']
                        ],
                        "components" => $whatsappLog->message['message_body']
                    ]
                ];

            } else {
                $data = [
                    'messaging_product' => 'whatsapp',
                    'to'                => $whatsappLog->meta_data['contact'],
                    'type'              => 'template',
                    "template" => [
                        "name" => $template->name,
                        "language" => [
                            "code" => $template->template_data['language']
                        ],
                        "components" => $whatsappLog->message['message_body']
                    ]
                ];
            }
            $customerService    = new CustomerService;
            
            $response           = Http::withHeaders($headers)->post($url, $data);
            $responseBody       = $response->body();
            $responseData       = json_decode($responseBody, true);
            if ($response->successful()) {

                $whatsappLog->response_message = $responseBody;
                $whatsappLog->status           = CommunicationStatusEnum::PROCESSING->value;
                $whatsappLog->update();

                if($whatsappLog->user_id) {
                            
                    $user        = User::find($whatsappLog->user_id);
                    $customerService->deductCreditLog($user, 1, ServiceType::WHATSAPP->value);
                }
            } else {
                if(isset($responseData['error']['message'])) {

                    self::processFailed($whatsappLog, $responseData['error']['message']);
                    \Log::error("WhatsApp dispatch fail: " . $responseData['error']['message']);
                } else {
                    self::processFailed($whatsappLog, $response->body());
                    \Log::error("WhatsApp dispatch fail: " . $response->body());
                }
            }
        } catch(\Exception $exception) {
          
            self::processFailed($whatsappLog, $exception->getMessage());
            \Log::error("WhatsApp dispatch fail: " . $exception->getMessage());
        }
    }

    /**
     * @param CommunicationLog $log
     * @param $status
     * @param $errorMessage
     * @return void
     */
    public static function addedCreditLog(CommunicationLog $log, $status, $errorMessage = null): void {
        
        $log->status           = $status;
        $log->response_message = !is_null($errorMessage) ? $errorMessage : null;
        $log->save();
        $user = User::find($log->user_id);

        if ($user && $status == CommunicationStatusEnum::FAIL->value) {

            if($log->whatsapp_template_id) {

                CustomerService::addedCreditLog($user, 1, ServiceType::WHATSAPP->value);
            } else {

                $messages    = str_split($log->message["message_body"], site_settings('whatsapp_word_count'));
                $totalcredit = count($messages);
                CustomerService::addedCreditLog($user, $totalcredit, ServiceType::WHATSAPP->value);
            }
        }
    }

    private static function processFailed($whatsapp_log, $message = "Failed To Connect Gateway") {

        $status = (string) CommunicationStatusEnum::FAIL->value;
        if($whatsapp_log->user_id) {

            SendWhatsapp::addedCreditLog($whatsapp_log, $status, $message);
        } else {

            $whatsapp_log->response_message = $message;
            $whatsapp_log->status = $status;
            $whatsapp_log->save();
        }
    }
}
