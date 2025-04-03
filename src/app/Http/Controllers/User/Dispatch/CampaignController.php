<?php

namespace App\Http\Controllers\User\Dispatch;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Exceptions\ApplicationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailCampaignRequest;
use App\Http\Requests\SmsCampaignRequest;
use App\Http\Requests\WhatsappCampaignRequest;
use App\Models\Campaign;
use App\Services\System\Contact\ContactService;
use App\Traits\ModelAction;
use Illuminate\Http\Request;
use App\Service\Admin\Dispatch\CampaignService;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Dispatch\EmailService;
use App\Service\Admin\Dispatch\SmsService;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Dispatch\WhatsAppService;
use App\Service\Admin\Gateway\EmailGatewayService;
use App\Service\Admin\Gateway\SmsGatewayService;
use Illuminate\Validation\ValidationException;
use App\Service\Admin\Gateway\WhatsappGatewayService;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CampaignController extends Controller
{
    use ModelAction;

    public $smsService;
    public $whatsappService;
    public $emailService;
    public $customerService;
    public $contactService;
    public $campaignService;
    public $smsGatewayService;
    public $whatsappGatewayService;
    public $emailGatewayService;

    public function __construct() {

        $this->customerService          = new CustomerService();
        $this->smsService               = new SmsService($this->customerService);
        $this->whatsappService          = new WhatsAppService($this->customerService);
        $this->emailService             = new EmailService($this->customerService);
        $this->contactService           = new ContactService();
        $this->smsGatewayService        = new SmsGatewayService();
        $this->whatsappGatewayService   = new WhatsappGatewayService();
        $this->emailGatewayService      = new EmailGatewayService();
        $this->campaignService          = new CampaignService($this->smsService, $this->emailService, $this->whatsappService, $this->contactService, $this->smsGatewayService, $this->whatsappGatewayService, $this->emailGatewayService);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index(): View {

        Session::put("menu_active", true);
        $title     = translate("Campaign Log");
        $user      = auth()->user();
        $campaigns = $this->campaignService->logs(auth()->user()->id);
        return view('user.communication.campaigns', compact('title', 'campaigns', 'user'));
    }
    
    public function createSms() {

        Session::put("menu_active", true);
        $type      = "sms";
        $user             = auth()->user();
        $remaining_credit = $user->sms_credit;
        $title     = translate("Create an SMS Camapign");
        $groups    = $this->smsService->getGroupWhereColumn('sms_contact', $user->id);
        $templates = $this->smsService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value, $user->id);
        $plan_access      = (object)planAccess($user);
        $api_gateways     = $this->smsService->smsGateway($plan_access->type); 
        $android_gateways = $this->smsService->androidGateways(StatusEnum::TRUE->status(), $plan_access->type);
        return view('user.communication.sms.campaign.create', compact('title', 'remaining_credit', 'groups', 'type', 'templates', 'plan_access', 'api_gateways', 'android_gateways'));
    }

    public function createWhatsapp() {

        Session::put("menu_active", true);
        $type      = "whatsapp";
        $user             = auth()->user();
        $title     = translate("Create an WhatsApp Camapign");
        $remaining_credit = $user->whatsapp_credit;
        $groups    = $this->whatsappService->getGroupWhereColumn('whatsapp_contact', $user->id);
        $templates   = $this->whatsappService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $plan_access      = (object)planAccess($user);
        $cloud_api_accounts = $this->whatsappService->gateways(StatusEnum::TRUE->status(), $user->id); 
        $devices     = $this->whatsappService->gateways(StatusEnum::FALSE->status(), $user->id);
        return view('user.communication.whatsapp.campaign.create', compact('title', 'remaining_credit', 'groups', 'type', 'templates', 'cloud_api_accounts', 'devices'));
    }

    public function createEmail() {

        Session::put("menu_active", true);
        $type      = "email";
        $user        = auth()->user();
        $title     = translate("Create an Email Camapign");
        $groups      = $this->emailService->getGroupWhereColumn('email_contact', $user->id);
        $templates   = $this->emailService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $plan_access = (object)planAccess($user);
        $gateways    = $this->emailService->gateways(StatusEnum::TRUE->status(), $plan_access->type); 
        return view('user.communication.email.campaign.create', compact('title', 'groups', 'type', 'templates', 'gateways', 'plan_access'));
    }

    public function editSms($id) {

        Session::put("menu_active", true);
        $type      = "sms";
        $user        = auth()->user();
        $title     = translate("Update SMS Camapign");
        $campaign  = Campaign::find($id);
        $groups    = $this->smsService->getGroupWhereColumn('sms_contact', $user->id);
        $templates = $this->smsService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $remaining_credit = $user->sms_credit;
        $plan_access      = (object)planAccess($user);
        $api_gateways     = $this->smsService->smsGateway($plan_access->type); 
        $android_gateways = $this->smsService->androidGateways(StatusEnum::TRUE->status(), $plan_access->type);
        return view('user.communication.sms.campaign.edit', compact('title', 'groups', 'type', 'remaining_credit', 'plan_access', 'templates', 'api_gateways', 'android_gateways', 'campaign'));
    }

    public function editWhatsapp($id) {

        Session::put("menu_active", true);
        $type      = "whatsapp";
        $user        = auth()->user();
        $title     = translate("Update WhatsApp Camapign");
        $campaign  = Campaign::find($id);
        $groups    = $this->smsService->getGroupWhereColumn('whatsapp_contact', $user->id);
        $templates   = $this->whatsappService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $remaining_credit = $user->whatsapp_credit;
        $plan_access      = (object)planAccess($user);
        $cloud_api_accounts = $this->whatsappService->gateways(StatusEnum::TRUE->status(), $user->id); 
        $devices     = $this->whatsappService->gateways(StatusEnum::FALSE->status(), $user->id);
        return view('user.communication.whatsapp.campaign.edit', compact('title', 'groups', 'remaining_credit', 'type', 'templates', 'cloud_api_accounts', 'devices', 'campaign'));
    }

    public function editEmail($id) {

        Session::put("menu_active", true);
        $type      = "email";
        $user        = auth()->user();
        $title     = translate("Update Email Camapign");
        $campaign  = Campaign::find($id);
        $groups    = $this->emailService->getGroupWhereColumn('email_contact', $user->id);
        $templates = $this->emailService->getTemplateWithStatusType(StatusEnum::TRUE->status(),constant(ServiceType::class . '::' . strtoupper($type))->value);
        
        $plan_access      = (object)planAccess($user);
        $gateways    = $this->emailService->gateways(StatusEnum::TRUE->status(), $plan_access->type); 
        return view('user.communication.email.campaign.edit', compact('title', 'groups', 'type', 'templates', 'gateways', 'campaign', 'plan_access'));
    }

    public function saveSms(SmsCampaignRequest $request, $type) {

        $status  = 'error';
        $message = "Something went wrong";
        try {
            $data = $request->all();
            unset($data['_token']);
            $user = auth()->user();
            list($status, $message) = $this->campaignService->save($type, $data, $user->id);
        } catch (\Exception $e) {
           
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    public function saveWhatsapp(WhatsappCampaignRequest $request, $type) {

        $status  = 'error';
        $message = "Something went wrong";
        try {
            
            $data = $request->all();
            unset($data['_token']);
            $user = auth()->user();
            list($status, $message) = $this->campaignService->save($type, $data, $user->id);
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

    public function saveEmail(EmailCampaignRequest $request, $type) {

        $status  = 'error';
        $message = "Something went wrong";
        try {

            $data = $request->all();
            unset($data['_token']);
            $user = auth()->user();
            list($status, $message) = $this->campaignService->save($type, $data, $user->id);
        } catch (\Exception $e) {
          
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }







    public function delete(Request $request) {

        $status = 'error';
        $message = "Something went wrong";
        try {
            if($request->has('id')) {
                
                $campaign = Campaign::find($request->input('id'));
                $campaign->communicationLog()->delete();
                $campaign->delete();
                $status = 'success';
                $message = translate("Campaign deleted successfully");
            } else {
                $message = translate("Campaign couldnt be found");
            }
            
        } catch (\Exception $e) {

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
    public function bulk(Request $request) :RedirectResponse {

        $status  = 'success';
        $message = translate("Successfully Performed bulk action");
        try {

            list($status, $message) = $this->bulkAction($request, null, [
                "model" => new Campaign(),
            ]);
    
        } catch (\Exception $exception) {

            $status  = 'error';
            $message = translate("Server Error: ").$exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws ValidationException If the validation fails.
     * 
     */
    public function statusUpdate(Request $request) {
      
        try {

            $this->validate($request,[

                'id'     => 'required',
                'value'  => 'required',
                'column' => 'required',
            ]); 
            $user = auth()->user();
            $notify = $this->campaignService->statusUpdate($request, $user->id);
            return $notify;

        } catch (ValidationException $validation) {

            return json_encode([
                
                'status'  => false,
                'message' => $validation->errors()
            ]);
        } 
    }
}