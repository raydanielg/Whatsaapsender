<?php

namespace App\Http\Controllers\User;

use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\CampaignSchedule;
use App\Models\Contact;
use App\Models\CreditLog;
use App\Models\EmailCreditLog;
use App\Models\EmailGroup;
use App\Models\EmailLog;
use App\Models\GeneralSetting;
use App\Models\Group;
use App\Models\Subscription;
use App\Models\Template;
use App\Models\WhatsappCreditLog;
use App\Rules\MessageFileValidationRule;
use App\Service\FileProcessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Service\Admin\Dispatch\CampaignService;
use App\Models\Gateway;
use App\Http\Requests\CampaignRequest;
use App\Models\AndroidApi;
use App\Models\ContactGroup;
use App\Models\PricingPlan;
use App\Models\SmsGateway;
use App\Models\WhatsappDevice;
use App\Models\WhatsappTemplate;
use Closure;

class CampaignController extends Controller
{
    public function __construct(protected CampaignService $campaignService)
    {
       
        $this->middleware(function (Request $request, Closure $next) {

            if(request()->type == 'sms') {

                if(Auth::user()->credit == 0  && in_array(str_replace('user.campaign.', '', Route::currentRouteName()),  ['send', 'create'])){
                    abort(403);
                }
            }  
            elseif(request()->type == 'Email') {
                
                if(Auth::user()->email_credit == 0  && in_array(str_replace('user.campaign.', '', Route::currentRouteName()),  ['send', 'create'])){
                    abort(403);
                }
            }
            else {

                if(Auth::user()->whatsapp_credit == 0  && in_array(str_replace('user.campaign.', '', Route::currentRouteName()),  ['send', 'create'])){
                    abort(403);
                }
            }
            return $next($request);
        });
    }

    /**
     * get all Campaign
     */
    public function index() {

        $channel   = $this->campaignService->getChannelFromRoute();
        $campaigns = Campaign::where('user_id',auth()->user()->id)->with('contacts')->where("channel",$channel)->paginate(paginateNumber(site_settings("paginate_number")));
        return view('user.campaign.index',[
            'campaigns' => $campaigns,
            'title'     => $this->campaignService->generateTitle($channel),
            'channel'   => $channel,
        ]);
    }

    /**
     * get all contacts by campaign id
     * @param $id
     */
    public function contactDelete(Request $request) {

       $campaignContact = CampaignContact::where('id',$request->id)->first();
       if($campaignContact) {

         $campaignContact->delete();
       }
       $notify[] = ['success', translate('Contact Deleted From Campaigns')];
       return back()->withNotify($notify);
       
    }

    /**
     * create a specific campaign
     * @return void
     */
    public function create($channel) {

        $user             = Auth::user();
        $title            = ucfirst($channel) . __(' Campaign Create');
        $groups           = $this->campaignService->getGroupsForChannel($channel);
        $android_gateways = AndroidApi::where("user_id", auth()->user()->id)->where("status", AndroidApi::ACTIVE)->latest()->get();
        $allowed_access   = planAccess($user);

        if ($allowed_access) {

            $allowed_access = (object)planAccess($user);

        } else {

            $notify[] = ['error','Please Purchase A Plan'];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }

        if ($channel == Campaign::EMAIL) {

            $templates      = $this->campaignService->getTemplatesForChannel($channel);
            $credentials    = config('setting.gateway_credentials.email');

            if($allowed_access->type == StatusEnum::FALSE->status() && $user->gateway->isNotEmpty() && $user->gateway()->mail()->active()->exists()) {

                return view('user.campaign.create', compact('title', 'channel', 'groups', 'templates', 'credentials', 'user', 'allowed_access'));
            } elseif($allowed_access->type == StatusEnum::TRUE->status()) {
                
                return view('user.campaign.create', compact('title', 'channel', 'groups', 'templates', 'credentials', 'user', 'allowed_access'));
            }
            else {
                $notify[] = ['error', 'Can Not Compose Mail Campaign. No Active Gateway Found'];
                return back()->withNotify($notify);
            }
           
        } elseif ($channel == Campaign::SMS) {

            $templates   = $this->campaignService->getTemplatesForChannel($channel);
            $credentials = SmsGateway::orderBy('id','asc')->get();
            

            if(($allowed_access->type == StatusEnum::FALSE->status() && $user->gateway->isNotEmpty() && $user->gateway()->sms()->active()->exists()) || auth()->user()->sms_gateway == 2) {

                return view('user.campaign.create', compact('title', 'channel', 'groups', 'templates', 'credentials', 'user', 'allowed_access', 'android_gateways'));
            } elseif($allowed_access->type == StatusEnum::TRUE->status()) {

                return view('user.campaign.create', compact('title', 'channel', 'groups', 'templates', 'credentials', 'user', 'allowed_access', 'android_gateways'));
            }
            else{
                $notify[] = ['error', 'Can Not Compose SMS Campaign. No Active Gateway Found'];
                return back()->withNotify($notify);
            }
           
        } elseif ($channel == Campaign::WHATSAPP) {

            $templates   = $this->campaignService->getTemplatesForChannel($channel);

            if ($allowed_access->type == StatusEnum::FALSE->status()) {

                $whatsapp_node_devices  = WhatsappDevice::where("user_id", $user->id)->where('status', WhatsappDevice::CONNECTED)->where("type", WhatsappDevice::NODE)->latest()->get();
                $whatsapp_bussiness_api = WhatsappDevice::where("user_id", $user->id)->where("type", WhatsappDevice::BUSINESS)->latest()->get();
                return view('user.campaign.create', compact('title', 'channel', 'groups', 'templates', 'whatsapp_bussiness_api', 'whatsapp_node_devices', 'allowed_access'));

            } else {

                $templates = $this->campaignService->getTemplatesForChannel($channel);
                $whatsapp_node_devices  = WhatsappDevice::whereNull("user_id")->where('status', WhatsappDevice::CONNECTED)->where("type", WhatsappDevice::NODE)->latest()->get();
                $whatsapp_bussiness_api = WhatsappDevice::where("user_id", $user->id)->where("type", WhatsappDevice::BUSINESS)->latest()->get();
                return view('user.campaign.create', compact('title', 'channel', 'groups', 'templates', 'user', 'allowed_access', 'whatsapp_bussiness_api', 'whatsapp_node_devices'));
            }
            
        } else {

            $title     = ucfirst($channel) . __(' Campaign Create');
            $groups    = $this->campaignService->getGroupsForChannel($channel);
            $templates = $this->campaignService->getTemplatesForChannel($channel);
            $credentials = SmsGateway::orderBy('id','asc')->get();
            return view('user.campaign.create', compact('title', 'channel', 'groups', 'templates', 'credentials', 'user', 'allowed_access'));
        }
    }

    /**
     * store a specific campaign
     *
     * @return void
     */
    public function store(CampaignRequest $request) {
       
        $attachableData = $this->campaignService->processContacts($request);
        $allowed_access   = (object) planAccess(auth()->user());
        if($request->input('channel') == Campaign::SMS) {

            if (auth()->user()->sms_gateway == 1) {

                $defaultGateway = $allowed_access->type == StatusEnum::FALSE->status() ? Gateway::whereNotNull('sms_gateways')->where("user_id", auth()->user()->id)->where('is_default', 1)->first() : 
                                                           Gateway::whereNotNull('sms_gateways')->whereNull("user_id")->where('is_default', 1)->first();
            } else {

                $defaultGateway = null;
            }
        }
        elseif($request->input('channel') == Campaign::EMAIL) {
            
            $defaultGateway = $allowed_access->type == StatusEnum::FALSE->status() ? Gateway::whereNotNull('mail_gateways')->where("user_id", auth()->user()->id)->where('is_default', 1)->first()
                              : Gateway::whereNotNull('mail_gateways')->whereNull("user_id")->where('is_default', 1)->first();
        
        } elseif ($request->input("channel") == Campaign::WHATSAPP) {
            
            if($request->input("whatsapp_sending_mode") == "without_cloud_api") {

                $defaultGateway = $request->input("whatsapp_device_id") == "-1" ? WhatsappDevice::where('admin_id', auth()->guard('admin')->user()->id)->where('status', 'connected')->pluck("credentials", "id")->toArray()
                               : WhatsappDevice::where('admin_id', auth()->guard('admin')->user()->id)->where("id", $request->input("whatsapp_device_id"))->where('status', 'connected')->pluck("credentials", "id")->toArray();
            } else {
                
            }
            
        }
        
        if(count($attachableData['contacts']) == 0) {

            $notify[] = ['error', translate('Select Some Audience!! Then Try Again ')];
            return back()->withNotify($notify);
        }

        if ($request->input('gateway_type')) {

            $gatewayMethod = Gateway::where('id', $request->input('gateway_id'))->firstOrFail();
        }
        else{

            if($request->input('channel') == Campaign::WHATSAPP || auth()->user()->sms_gateway == 2) {

                $gatewayMethod = null;
            }
            else{
                if($defaultGateway) {
                
                    $gatewayMethod = $defaultGateway;
                }
                else {
                    $notify[] = ['error', 'You Do Not Have Any Default Gateway.'];
                    return back()->withNotify($notify);
                }
            }
            
        }
        $templateData = null;
        if($request->input("channel") == "whatsapp" && $request->input("cloud_api") == "true") {

            $templateData = WhatsappTemplate::find($request->input("whatsapp_template_id"));
        }
       
        $campaign = $this->campaignService->save($request, $gatewayMethod, $templateData);

        if ($request->input('repeat_number')) {

            $this->campaignService->saveSchedule($request, $campaign->id);
        }

       
       
        $this->campaignService->saveContacts($attachableData, $campaign);
        $notify[]     = ['success', translate('The campaign has been successfully created.')];
        return back()->withNotify($notify);
    }

    public function insertContacts($attachableData, $campaign) {

        $contactNewArray = array_unique($attachableData['contacts']);

        $groupName = $attachableData['contact_with_name'];
        $data = [];
        foreach($contactNewArray as $key => $value) {
            $content = $campaign->body;
            if(array_key_exists($value,$groupName)){
                $content  = str_replace('{{name}}', $groupName ? $groupName[$value]:$value, $content);
            }
            $arr = array(
                'campaign_id' => $campaign->id,
                'contact'     => $value,
                'message'     => $content,
            );
            array_push($data, $arr);

        }
        
        $campaignContact = CampaignContact::insert($data);
    }

    public function createCampaignSchedule($request,$campaignId) {
        
        $campaignSchedule                = new CampaignSchedule();
        $campaignSchedule->campaign_id   = $campaignId;
        $campaignSchedule->repeat_number = $request->repeat_number;
        $campaignSchedule->repeat_format = $request->repeat_format;
        $campaignSchedule->save();
    }


    public static function processRelationalData($request) {
        
        $groupName = []; 
        $contacts  = [];
        if( $request->group) {

            $group     = Contact::whereNotNull('user_id')->whereIn('group_id', $request->group)->pluck('email_contact')->toArray();
            $groupName = Contact::whereNotNull('user_id')->whereIn('group_id', $request->group)->pluck('first_name','email_contact')->toArray();
            array_push($contacts, $group);
        }
   
        if($request->has('file')){
            
            $service   = new FileProcessService();
            $extension = strtolower($request->file->getClientOriginalExtension());
            if(!in_array($extension, ['csv','xlsx'])) {

                $notify[] = ['error', 'Invalid file extension'];
                return back()->withNotify($notify);
            }
            if($extension == "csv") {
                $response =  $service->processCsv($request->file);
             
                array_push($contacts,array_keys($response));
                if($request->channel == Campaign::EMAIL){
                    $groupName = array_merge($groupName, $response);
                }
                else{
                    $groupName = $groupName + $response;
                }
               
            };
            if($extension == "xlsx") {
                $response =  $service->processExel($request->file);
                array_push($contacts,array_keys($response));
                if($request->channel == Campaign::EMAIL){
                    $groupName = array_merge($groupName, $response);
                }
                else{
                    $groupName = $groupName + $response;
                }
            }
        }
       
        $contactNewArray = [];
        foreach($contacts as $childArray){
            foreach($childArray as $value){
                $contactNewArray[] = $value;
            }
        }
     
        return ([
            "contacts" => $contactNewArray,
            "contact_with_name" => $groupName,
        ]);
    }



    /**
     *  get a specific Campaign template json
     * @param $id
     *
     */
    public function search(Request $request)
    {
        $request->validate([
            "channel" => 'required',
        ]);
        $search        = $request->search;
        $channel       = $request->channel;
        $searchStatus  = null;
        $campaigns     =  Campaign::where('user_id',auth()->user()->id)->where('channel',$request->channel);
        if( $search){
            $campaigns = $campaigns->where('name',"like","%".$search."%");
        }
 
        if($request->status){
            $searchStatus = $request->status;
            $campaigns    = $campaigns->where('status',$searchStatus ) ;
        }
        $campaigns = $campaigns->paginate(paginateNumber(site_settings("paginate_number")));
        return view('user.campaign.index',[
            'campaigns'    =>  $campaigns ,
            'title'        =>  $channel.translate(' Campaign Search') ,
            'channel'      =>  $channel ,
            'search'       =>  $search ,
            'searchStatus' =>  $searchStatus ,
        ]);
    }


    /**
     *  edit a specific Campaign
     * @param $id
     *
     */
    public function edit($type, $id)
    {
        $campaign              = Campaign::where('user_id',auth()->user()->id)->with("schedule")->with('contacts')->where('id',$id)->first();
        $user                  = Auth::user();
        $templates             = [];
        $title                 = ucfirst($campaign->channel) . __(' Campaign Update');
        $allowed_access        = planAccess($user);
        $android_gateways      = AndroidApi::where("user_id", auth()->user()->id)->where("status", AndroidApi::ACTIVE)->latest()->get();
        $whatsapp_node_devices = WhatsappDevice::where("user_id", $user->id)->where('status', WhatsappDevice::CONNECTED)->where("type", WhatsappDevice::NODE)->latest()->get();
        $whatsapp_bussiness_api = WhatsappDevice::where("user_id", $user->id)->where("type", WhatsappDevice::BUSINESS)->latest()->get();
        if ($allowed_access) {

            $allowed_access = (object)planAccess($user);
        } else {

            $notify[] = ['error','Please Purchase A Plan'];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }
        $credentials = [];
      
        if ($campaign->channel == ServiceType::EMAIL) {

            $credentials = config('setting.gateway_credentials.email');
            $groups      = ContactGroup::where('user_id',auth()->user()->id)->get();
        }
        else {

            $templates   = Template::where('user_id',auth()->user()->id)->get();
            $groups      = ContactGroup::where('user_id',auth()->user()->id)->get();
            $credentials = SmsGateway::orderBy('id','asc')->get();
        }
      
        return view('user.campaign.edit',[
            'title'                  => $title,
            'campaign'               => $campaign ,
            'channel'                => $campaign->channel ,
            'groups'                 => $groups ,
            'templates'              => $templates ,
            'credentials'            => $credentials,
            'user'                   => $user,
            'allowed_access'         => $allowed_access,
            'android_gateways'       => $android_gateways,
            'whatsapp_node_devices'  => $whatsapp_node_devices,
            'whatsapp_bussiness_api' => $whatsapp_bussiness_api,
        ]);
    }


    /**
     *  Preview a Specific Campaign
     * @param $id
     *
     */
    public function contacts($id)
    {
        $title    = translate('Campaign Contact List');
        $campaign = Campaign::with('contacts')->where('id',$id)->first();
        $contacts = CampaignContact::where('campaign_id',$id)->paginate(paginateNumber(site_settings("paginate_number")));
        return view('user.campaign.show',[
              'title'    => $title ,
              'contacts' => $contacts,
              'campaign' => $campaign 
        ]);
    }
   
    
    /**
     *  update a specific sms gatway
     *
     * @return void
     */
    public function update(CampaignRequest $request)
    {
        $contactsData = $this->campaignService->processContacts($request);

        if($request->input('channel') == Campaign::SMS) {

            $defaultGateway = auth()->user()->sms_gateway == 1 ? Gateway::whereNotNull('sms_gateways')->where("user_id", auth()->user()->id)->where('is_default', 1)->first() : null;
        }
        elseif($request->input('channel') == Campaign::EMAIL) {
            $defaultGateway = Gateway::whereNotNull('mail_gateways')->where("user_id", auth()->user()->id)->where('is_default', 1)->first();
        }

        if (count($contactsData['contacts']) == 0) {

            $notify[] = ['error', translate("A campaign cannot be updated without contacts.")];
            return back()->withNotify($notify);
        }

        if($request->input('gateway_type')) {

            $gatewayMethod = Gateway::where('id', $request->input('gateway_id'))->firstOrFail();
        }
        else{
            if($request->input('channel') == Campaign::WHATSAPP) {
                $gatewayMethod = null;
            }else{
                if($request->input('channel') == Campaign::WHATSAPP || auth()->user()->sms_gateway == 2) {
                    $gatewayMethod = null;
                } else {
                    if($defaultGateway) {
                
                        $gatewayMethod = $defaultGateway;
                    }
                    else {
                        $notify[] = ['error', 'You Do Not Have Any Default Gateway.'];
                        return back()->withNotify($notify);
                    }
                }
            }
        }
        $templateData = null;
        if($request->input("channel") == "whatsapp" && $request->input("cloud_api") == "true") {

            $templateData = WhatsappTemplate::find($request->input("whatsapp_template_id"));
        }
        $campaign = $this->campaignService->save($request, $gatewayMethod, $templateData);

        if ($request->input('repeat_number')) {

            CampaignSchedule::where('campaign_id',$campaign->id)->delete();
            $this->campaignService->saveSchedule($request, $campaign->id);
        }
        CampaignContact::where('campaign_id',$campaign->id)->delete();
        $this->campaignService->saveContacts($contactsData, $campaign);
        $notify[]     = ['success', translate('Campaign Updated Successfully')];
        return back()->withNotify($notify);
        
    }

    /**
     * destory a specific camapign
     *
     * @param $id
     */

    public function delete(Request $request) {
        
        $campaign = Campaign::with('contacts')->where('user_id',auth()->user()->id)->where('id',$request->id)->first();
        if($campaign) {

            CampaignContact::where('campaign_id',$campaign->id)->delete();
            CampaignSchedule::where('campaign_id',$campaign->id)->delete();
            $campaign->delete();
        }
        $notify[] = ['success', translate('Campaign Deleted')];
        return back()->withNotify($notify);
    }
}
