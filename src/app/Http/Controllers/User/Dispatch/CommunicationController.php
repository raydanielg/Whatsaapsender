<?php

namespace App\Http\Controllers\User\Dispatch;


use App\Enums\StatusEnum;
use Illuminate\View\View;
use App\Enums\ServiceType;
use Illuminate\Http\Request;
use App\Models\CommunicationLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Enums\CommunicationStatusEnum;
use App\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Dispatch\SmsService;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Dispatch\EmailService;
use App\Service\Admin\Dispatch\WhatsAppService;
use App\Http\Requests\Admin\CommunicationRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    public $smsService;
    public $whatsappService;
    public $emailService;
    public $customerService;
    public function __construct() {

        $this->customerService = new CustomerService();
        $this->smsService      = new SmsService($this->customerService);
        $this->whatsappService = new WhatsAppService($this->customerService);
        $this->emailService    = new EmailService($this->customerService);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function smsList($campaign_id = null): View {

        Session::put("menu_active", true);
        $title            = translate("SMS Log");
        $user             = auth()->user();
        $plan_access      = (object)planAccess($user);
        $logs             = $this->smsService->logs($user->id, $campaign_id);
        return view('user.communication.sms.index', compact('title', 'logs', 'plan_access'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function whatsappList($campaign_id = null): View {

        Session::put("menu_active", true);
        $title     = translate("WhatsApp Log");
        $user      = auth()->user();
        $logs      = $this->whatsappService->logs($user->id, $campaign_id);
        return view('user.communication.whatsapp.index', compact('title', 'logs'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function emailList($campaign_id = null): View {

        Session::put("menu_active", true);
        $title    = translate("Email Log");
        $user     = auth()->user();
        $logs     = $this->emailService->logs($user->id, $campaign_id);
        return view('user.communication.email.index', compact('title', 'logs'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function createSms(): View {

        Session::put("menu_active", true);
        $type             = "sms";
        $user             = auth()->user();
        $remaining_credit = $user->sms_credit;
        $title            = ucfirst($type);
        $column_name      = $type . '_contact';
        $groups           = $this->smsService->getGroupWhereColumn($column_name, $user->id);
        $templates        = $this->smsService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value, $user->id);
        $plan_access      = (object)planAccess($user);
        $api_gateways     = $this->smsService->smsGateway($plan_access->type, $user->id); 
        $android_gateways = $this->smsService->androidGateways(StatusEnum::TRUE->status(), $plan_access->type);
        return view("user.communication.$type.create", compact('title', 'templates', 'api_gateways', 'groups', 'android_gateways', 'type', 'remaining_credit', 'user', 'plan_access'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function createWhatsapp(): View {

        Session::put("menu_active", true);
        $type        = "whatsapp";
        $user        = auth()->user();
        $title       = ucfirst($type);
        $remaining_credit = $user->whatsapp_credit;
        $column_name = $type . '_contact';
        $groups      = $this->whatsappService->getGroupWhereColumn($column_name, $user->id);
        $templates   = $this->whatsappService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $plan_access      = (object)planAccess($user);
        $cloud_api_accounts = $this->whatsappService->gateways(StatusEnum::TRUE->status(), $user->id); 
        $devices     = $this->whatsappService->gateways(StatusEnum::FALSE->status(), $user->id);
        return view("user.communication.$type.create", compact('title', 'templates', 'cloud_api_accounts', 'groups', 'devices', 'type', 'remaining_credit', 'user', 'plan_access'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function createEmail(): View {

        Session::put("menu_active", true);
        $credentials = config('setting.gateway_credentials.email');
        $type        = "email";
        $user        = auth()->user();
        $title       = ucfirst($type);
        $column_name = $type . '_contact';
        $groups      = $this->emailService->getGroupWhereColumn($column_name, $user->id);
        $templates   = $this->emailService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $plan_access = (object)planAccess($user);
        $gateways    = $this->emailService->gateways(StatusEnum::TRUE->status(), $plan_access->type); 
        return view("user.communication.$type.create", compact('title', 'templates', 'gateways', 'groups', 'type', 'user', 'plan_access', 'credentials'));
    }

    public function viewEmailBody($id) {

        $title     = translate("Details View");
        $emailLogs = CommunicationLog::where('id',$id)->orderBy('id', 'DESC')->limit(1)->first();
        return view('partials.email_view', compact('title', 'emailLogs'));
    }

    /**
     * @param CommunicationRequest $request
     * 
     * @param string $type
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function store(CommunicationRequest $request, string $type): RedirectResponse {

        $status  = 'error';
        $message = "Something went wrong";
        
        try {
            $data = $request->all();
            unset($data['_token']);
            if($type == 'sms') {
                
                list($status, $message) = $this->smsService->store($type, $data, $request->hasFile('contacts') ? 'file' : (is_array($request->input('contacts')) ? 'array' : 'text'), auth()->user()->id);
            } elseif($type == 'whatsapp') {

                list($status, $message) = $this->whatsappService->store($type, $data, $request->hasFile('contacts') ? 'file' : (is_array($request->input('contacts')) ? 'array' : 'text'), auth()->user()->id);
            } else {
                
                list($status, $message) = $this->emailService->store($type, $data, $request->hasFile('contacts') ? 'file' : (is_array($request->input('contacts')) ? 'array' : 'text'), auth()->user()->id);
            }
        } catch (ApplicationException $e) {
            
            $notify[] = ["error", translate($e->getMessage())];
            return back()->withNotify($notify);

        } catch (Exception $e) {
            
            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return back()->withNotify($notify);
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function statusUpdate(Request $request): RedirectResponse {

        $status  = "error";
        $message = "Something went wrong";

        try {
            $data = $request->all();
            unset($data['_token']);
            $log = $this->getLogById($data['id']);
            if($log->campaign_id) {
                
                $notify[] = ["error", translate("You can not update campaign status")];
                return back()->withNotify($notify);
            }
            if($log->type == ServiceType::SMS->value) {

                list($status, $message) = $this->smsService->statusUpdate($data, $log);
            } elseif($log->type == ServiceType::WHATSAPP->value) {

                list($status, $message) = $this->whatsappService->statusUpdate($data, $log);
            } elseif($log->type == ServiceType::EMAIL->value) {

                list($status, $message) = $this->emailService->statusUpdate($data, $log);
            }
            
            
        } catch (\Exception $e) {
            
            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function delete(Request $request): RedirectResponse {

        $status  = "error";
        $message = "Something went wrong";

        try {

            $communication_log = CommunicationLog::where('id', $request->input('id'))->first();
            if($communication_log) {

                $communication_log->delete();
                $status  = "success";
                $message = translate("SMS Log deleted successfully");
            } else {

                $status  = "success";
                $message = translate("Log couldnt be found");
            }

        } catch (\Exception $e) {

            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

     /**
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function bulk(Request $request, $type = null) :RedirectResponse {

        $status  = 'success';
        $message = translate("Successfully Performed bulk action");
        try {
            if($type == ServiceType::SMS->value) {

                list($status, $message) = $this->smsService->bulkAction($request, $type, [
                    "model" => new CommunicationLog(),
                ]);
            } elseif($type == ServiceType::WHATSAPP->value) {

                list($status, $message) = $this->whatsappService->bulkAction($request, $type, [
                    "model" => new CommunicationLog(),
                ]);
            } elseif($type == ServiceType::EMAIL->value) {

                list($status, $message) = $this->emailService->bulkAction($request, $type, [
                    "model" => new CommunicationLog(),
                ]);
            }
           
    
        } catch (\Exception $exception) {
            
            $status  = 'error';
            $message = translate("Server Error: ").$exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function api(Request $request) {

        Session::put("menu_active", false);
        $title   = translate("API Document");
        $api_key = auth()->user()->api_key;
        $user = auth()->user();
        $plan_access = (object) planAccess($user);
        if($request->ajax()) {

            $status = 'error';
            $message = translate("Something went wrong");
            try {
                $user->api_key = $request->has('api_key') ? $request->input('api_key') : $user->api_key;
                $user->api_sms_method = $request->has('api_sms_method') ? $request->input('api_sms_method') : $user->api_sms_method;
                $user->save();
                $status = 'success';
                $message = translate("User API Key has been saved successfully");
            } catch(\Exception $e) {

                $message = translate("Server Error");
            }
            return response()->json([
               'status'  => $status, 
               'message' => $message
            ],'200');
        }
        return view('user.communication.api', compact('title', 'api_key', 'user', 'plan_access'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function apiSave(Request $request, $type = null) {

        $user = User::where('id', auth()->user()->id)->first();
        $status = 'error';
        $message = "Something went wrong";
        try {

            $data = $request->toArray();
            unset($data['_token']);
            
            if(ServiceType::SMS->value == $type) {
                
                $user->api_sms_method = array_key_exists('api_sms_method', $data) ? $data['api_sms_method'] : $user->api_sms_method;
                
            }
            $user->update();
            $status = "success";
            $message = ServiceType::getValue($type).translate(" API Method updated successfully");

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    public function getLogById($id) {

        return CommunicationLog::where('id', $id)->first();
    }
}
