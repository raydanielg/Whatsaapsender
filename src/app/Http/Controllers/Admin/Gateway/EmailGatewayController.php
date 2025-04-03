<?php

namespace App\Http\Controllers\Admin\Gateway;

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

        Session::put("menu_active", true);
    	$title       = translate("Email API Gateway list");
        $credentials = config('setting.gateway_credentials.email');
        $gateway     = Gateway::whereNotNull('mail_gateways')->whereNull('user_id')->where('uid', $request->uid);
    	$gateways    = Gateway::whereNull('user_id')
                                ->whereNotNull('mail_gateways')
                                ->orderBy('is_default', 'DESC')
                                ->search(['name'])
                                ->filter(['status'])
                                ->date()
                                ->paginate(paginateNumber(site_settings("paginate_number")));
        
    	return view('admin.gateway.email.index', compact('title', 'gateways', 'credentials'));
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

            $gateway = $this->emailGatewayService->save($request);
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

            $gateway = $this->emailGatewayService->save($request);
            $status  = 'success';
            $message = ucfirst($gateway->type). ' gateway under: '.ucfirst($gateway->name). ' has been updated';

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

            $notify = $this->emailGatewayService->statusUpdate($request);
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

            $mailGateway = Gateway::whereNull("user_id")
                                        ->whereNotNull('mail_gateways')
                                        ->where('is_default', 1)
                                        ->first();

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
