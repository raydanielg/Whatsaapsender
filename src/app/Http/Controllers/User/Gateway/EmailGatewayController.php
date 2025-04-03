<?php

namespace App\Http\Controllers\User\Gateway;

use App\Enums\StatusEnum;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\Gateway\EmailGatewayService;
use App\Http\Requests\EmailGatewayRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class EmailGatewayController extends Controller
{
    public $emailGatewayService;
    public function __construct() {
        
        $this->emailGatewayService = new EmailGatewayService();
    }

    /**
      * 
      * @param Request $request
      *
      * @return \Illuminate\View\View
      * 
     */
    public function index(Request $request) {
        
        Session::put("menu_active", false);
        $user             = auth()->user();
        $allowed_access   = planAccess($user);
        if($allowed_access) {
            $allowed_access   = (object)planAccess($user);
        } else {
            $notify[] = ['error', translate('Please Purchase A Plan')];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }
    	$title       = translate("Email API Gateway list");
        $credentials = config('setting.gateway_credentials.email');
        $gateways_query = Gateway::whereNotNull('mail_gateways')
                        ->orderBy('is_default', 'DESC')
                        ->search(['name'])
                        ->filter(['status'])
                        ->date();
        $allowed_access->type == StatusEnum::FALSE->status() ? 
        $gateways_query->where('user_id', $user->id) :
        $gateways_query->whereNull('user_id');
        $gateways = $gateways_query->paginate(paginateNumber(site_settings("paginate_number")));
        $gatewaysForCount = $allowed_access->type == StatusEnum::FALSE->status() ? 
            Gateway::where('user_id', $user->id)->whereNotNull('mail_gateways')->where('status', StatusEnum::TRUE->status())->get()
            : Gateway::whereNull('user_id')->whereNotNull('mail_gateways')->where('status', StatusEnum::TRUE->status())->get();  
        $gatewayCount     = $gatewaysForCount->groupBy('type')->map->count(); 
            
    	return view('user.gateway.email.index', compact('title', 'gateways', 'credentials', 'gatewaysForCount', 'gatewayCount', 'allowed_access', 'user'));
    }

    /**
     *
     * @param EmailGatewayRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function store(EmailGatewayRequest $request) {

        $status  = 'error';
        $message = translate("Something went wrong");
        try {

            $gateway = $this->emailGatewayService->save($request, auth()->user()->id);
            $status  = 'success';
            $message = 'A new '.ucfirst($gateway->type). ' gateway has been created under: '.ucfirst($gateway->name);

        } catch (\Exception $e) {

            $message = translate("Server Error: ") . $e->getMessage();
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     *
     * @param EmailGatewayRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function update(EmailGatewayRequest $request) {

        $status  = 'error';
        $message = translate("Something went wrong");
        try {
            $type = $request->input('type');
            $user = auth()->user();
            $plan = $user->runningSubscription()->currentPlan()->email->allowed_gateways;
            $gateways     = Gateway::where('user_id', $user->id)->whereNotNull('mail_gateways')->where('status', StatusEnum::TRUE->status())->get();
            $gatewayCount = $gateways->groupBy('type')->map->count(); 
            
            if($gatewayCount->get($type, 0) < collect($plan)->get($type, 0) || $request->input('status') == 1) {

                if(array_key_exists($request->type, (array)$plan)) { 
    
                    $gateway = $this->emailGatewayService->save($request, auth()->user()->id);
                    $status  = 'success';
                    $message = ucfirst($gateway->type). ' gateway under: '.ucfirst($gateway->name). ' has been updated';
                }
                else {

                    $status = "error";
                    $message = translate("You Do Not Have The Permission To Create ").strtoupper($request->input('type')).translate(" Gateway!");
                   
                }
            }
            else {
                $status = "error";
                $message = translate("Cannot exceed allowed gateway amount per type");
               
            }
           

        } catch (\Exception $e) {
            
            $message = translate("Server Error: ") . $e->getMessage();
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

            $notify = $this->emailGatewayService->statusUpdate($request, $user->id);
            return $notify;

        } catch (ValidationException $validation) {

            return json_encode([
                'status'  => false,
                'message' => $$validation->errors()
            ]);
        } 
    }

    /**
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request) {
       
        $status  = 'success';
        $message = translate('Gateway has been successfully deleted');
        try {
            
            $gateway = Gateway::find($request->id);
            if($gateway) {

                $gateway->delete();
            } else {
                
                $status = "error";
                $message = translate("Couldn't find gateway");
            }
            
            
        } catch(\Exception $e) {

            $status  = 'success';
            $message = $e->getMessage();
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
    public function testGateway(Request $request) :mixed {
        
        try {

            $mailGateway = Gateway::whereNotNull('mail_gateways')->where('is_default', 1)->first();

            if($mailGateway == null) {

                return json_encode([

                    'address' => translate('No default mail gateway found'), 
                    'status'  => false,
                ]);
            }
            $response = $this->emailGatewayService->gatewayTest($mailGateway, $request->input('email'));
            return $response;

        } catch (\Exception $e) {

            return json_encode([

                'address' => $e->getMessage(), 
                'status'  => false,
            ]);
        }    
    }
}
