<?php

namespace App\Http\Controllers\Admin\Dispatch;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailCampaignRequest;
use App\Http\Requests\SmsCampaignRequest;
use App\Http\Requests\WhatsappCampaignRequest;
use App\Models\Campaign;
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
use App\Service\Admin\Gateway\WhatsappGatewayService;
use App\Services\System\Contact\ContactService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CampaignController extends Controller
{
    use ModelAction;

    public $smsService;
    public $whatsappService;
    public $emailService;
    public $customerService;
    public $campaignService;
    public $smsGatewayService;
    public $whatsappGatewayService;
    public $emailGatewayService;
    public $contactService;

    public function __construct() {

        $this->customerService = new CustomerService();
        $this->smsService      = new SmsService($this->customerService);
        $this->whatsappService = new WhatsAppService($this->customerService);
        $this->emailService    = new EmailService($this->customerService);
        $this->contactService   = new ContactService();
        $this->smsGatewayService = new SmsGatewayService();
        $this->whatsappGatewayService = new WhatsappGatewayService();
        $this->emailGatewayService = new EmailGatewayService();
        $this->campaignService = new CampaignService($this->smsService, $this->emailService, $this->whatsappService, $this->contactService, $this->smsGatewayService, $this->whatsappGatewayService, $this->emailGatewayService);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index(): View {

        Session::put("menu_active", true);
        $title     = translate("Campaign Log");
        $campaigns = $this->campaignService->logs();
        return view('admin.communication.campaigns', compact('title', 'campaigns'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function unsubscriptions($type = null): View {
        
        Session::put("menu_active", true);
        $unsubscriptions = $this->campaignService->unsubscriptions($type);
        $type_name       = strtolower(ServiceType::getValue($type));
        $title           = translate("$type_name Campaign Unsubscription Log");
        return view("admin.communication.$type_name.campaign.unsubscriptions", compact('title', 'unsubscriptions', 'type'));
    }
    
    public function createSms() {

        Session::put("menu_active", true);
        $type      = "sms";
        $title     = translate("Create an SMS Camapign");
        $groups    = $this->smsService->getGroupWhereColumn('sms_contact');
        $templates = $this->smsService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $api_gateways     = $this->smsService->smsGateway(); 
        $android_gateways = $this->smsService->androidGateways(StatusEnum::TRUE->status());
        return view('admin.communication.sms.campaign.create', compact('title', 'groups', 'type', 'templates', 'api_gateways', 'android_gateways'));
    }

    public function createWhatsapp() {

        Session::put("menu_active", true);
        $type      = "whatsapp";
        $title     = translate("Create an WhatsApp Camapign");
        $groups    = $this->whatsappService->getGroupWhereColumn('whatsapp_contact');
        $templates   = $this->whatsappService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $cloud_api_accounts = $this->whatsappService->gateways(StatusEnum::TRUE->status()); 
        $devices     = $this->whatsappService->gateways(StatusEnum::FALSE->status());
        return view('admin.communication.whatsapp.campaign.create', compact('title', 'groups', 'type', 'templates', 'cloud_api_accounts', 'devices'));
    }

    public function createEmail() {

        Session::put("menu_active", true);
        $type      = "email";
        $title     = translate("Create an Email Camapign");
        $groups      = $this->emailService->getGroupWhereColumn('email_contact');
        $templates   = $this->emailService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $gateways    = $this->emailService->gateways(StatusEnum::TRUE->status()); 
        return view('admin.communication.email.campaign.create', compact('title', 'groups', 'type', 'templates', 'gateways'));
    }

    public function editSms($id) {

        Session::put("menu_active", true);
        $type      = "sms";
        $title     = translate("Update SMS Camapign");
        $campaign  = Campaign::find($id);
        $groups    = $this->smsService->getGroupWhereColumn('sms_contact');
        $templates = $this->smsService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $api_gateways     = $this->smsService->smsGateway(); 
        $android_gateways = $this->smsService->androidGateways(StatusEnum::TRUE->status());
        return view('admin.communication.sms.campaign.edit', compact('title', 'groups', 'type', 'templates', 'api_gateways', 'android_gateways', 'campaign'));
    }

    public function editWhatsapp($id) {

        Session::put("menu_active", true);
        $type      = "whatsapp";
        $title     = translate("Update WhatsApp Camapign");
        $campaign  = Campaign::find($id);
        $groups    = $this->smsService->getGroupWhereColumn('whatsapp_contact');
        $templates   = $this->whatsappService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $cloud_api_accounts = $this->whatsappService->gateways(StatusEnum::TRUE->status()); 
        $devices     = $this->whatsappService->gateways(StatusEnum::FALSE->status());
        return view('admin.communication.whatsapp.campaign.edit', compact('title', 'groups', 'type', 'templates', 'cloud_api_accounts', 'devices', 'campaign'));
    }

    public function editEmail($id) {

        Session::put("menu_active", true);
        $type      = "email";
        $title     = translate("Update Email Camapign");
        $campaign  = Campaign::find($id);
        $groups    = $this->emailService->getGroupWhereColumn('email_contact');
        $templates = $this->emailService->getTemplateWithStatusType(StatusEnum::TRUE->status(),constant(ServiceType::class . '::' . strtoupper($type))->value);
        $gateways  = $this->emailService->gateways(StatusEnum::TRUE->status()); 
        return view('admin.communication.email.campaign.edit', compact('title', 'groups', 'type', 'templates', 'gateways', 'campaign'));
    }

    public function saveSms(SmsCampaignRequest $request, $type) {

        $status  = 'error';
        $message = "Something went wrong";
        try {

            $data = $request->all();
            unset($data['_token']);
            list($status, $message) = $this->campaignService->save($type, $data);
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
            list($status, $message) = $this->campaignService->save($type, $data);
        } catch (\Exception $e) {

            $message = translate("Server Error");
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
            list($status, $message) = $this->campaignService->save($type, $data);
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
}