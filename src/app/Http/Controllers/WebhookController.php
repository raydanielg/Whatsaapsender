<?php

namespace App\Http\Controllers;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommunicationLog;
use App\Models\PostWebhookLog;
use App\Service\Admin\Dispatch\WhatsAppService;
use Carbon\Carbon;

class WebhookController extends Controller
{
    public function postWebhook(Request $request, WhatsAppService $whatsAppService) {
        
        try {

            if ($request->isMethod('get')) {

                $apiKey     = site_settings("webhook_verify_token");
                $query      = $request->query();
                $hubMode    = $query["hub_mode"] ?? null;
                $hubToken   = $query["hub_verify_token"] ?? null;
                $challenge  = $query["hub_challenge"] ?? null;
                $usersCount = User::where("webhook_token", $hubToken)->count();
                
                if ($hubMode && $hubToken && $hubMode === 'subscribe' && ($hubToken === $apiKey || $usersCount > 0)) {

                    return response($challenge, 200)->header('Content-Type', 'text/plain');
                } else {

                    throw new Exception("Invalid Request");
                }
            } else {

                $request      = request()->all();
                \Log::info("Request Data: " . $request);
                $user         = User::where('uid',request()->input('uid'))->first();
                \Log::info("User Log: " . $user);
                $webhookLog   = PostWebhookLog::create([
                    'user_id'           => $user ? $user->id : null,
                    'webhook_response'  => json_encode($request)
                ]);
                \Log::info("Webhook Log: " . $webhookLog);
                $response      = json_decode($webhookLog->webhook_response, true);
                \Log::info("Response Data: " . $response);
                $idFromRequest = $response["entry"][0]['changes'][0]['value']['statuses'][0]['id'] ?? null;
        
                if ($idFromRequest) {

                    $whatsappLog = CommunicationLog::where("type", ServiceType::WHATSAPP->value)->whereJsonContains('response_message->messages', [['id' => $idFromRequest]])->first();
                    \Log::info("WhatsApp Log Data: " . $whatsappLog);
                    if ($whatsappLog) {

                        $errors = $response['entry'][0]['changes'][0]['value']['statuses'][0]['errors'] ?? [];
                        if (!empty($errors)) {

                            $whatsappLog->status = (string) CommunicationStatusEnum::FAIL->value;
                            $whatsAppService->addedCreditLog($whatsappLog, $errors[0]['message']);
                            $whatsappLog->save();
                        } else {
                            
                            $status = $response['entry'][0]['changes'][0]['value']['statuses'][0]['status'];
                            if ($status == 'failed') {

                                $whatsappLog->status = (string) CommunicationStatusEnum::FAIL->value;
                                $whatsAppService->addedCreditLog($whatsappLog, "Cloud API couldnt send the message.");
                            } elseif ($status == 'sent') {
                                $meta_data = $whatsappLog->meta_data;
                                $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
                                $whatsappLog->meta_data = $meta_data;
                                $whatsappLog->status = (string) CommunicationStatusEnum::DELIVERED->value;
                            }
                            $whatsappLog->save();
                        }
                    }  
                }
            }
        } catch (Exception $e) {
            \Log::error("Exception Data: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
