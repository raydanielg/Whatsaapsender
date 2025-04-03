<?php

namespace App\Http\Controllers\Admin\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SmsGatewayRequest;
use Illuminate\Http\Request;
use App\Models\SmsGateway;
use App\Models\Gateway;
use App\Service\Admin\Gateway\SmsGatewayService;
use App\Traits\ModelAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class SmsGatewayController extends Controller
{
    use ModelAction;
    public $smsGatewayService;
    public function __construct() {

        $this->smsGatewayService = new SmsGatewayService();
    }

    /**
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index() {

    	$title       = translate("SMS API Gateway list");
    	$sms_gateways = Gateway::whereNull('user_id')
                                ->whereNotNull('sms_gateways')
                                ->orderBy('is_default', 'DESC')
                                ->search(['name'])
                                ->filter(['status'])
                                ->date()
                                ->paginate(paginateNumber(site_settings("paginate_number")));

    	$credentials = SmsGateway::orderBy('id','asc')->get();
    	return view('admin.gateway.sms.api.index', compact('title', 'sms_gateways', 'credentials'));
    }

    /**
     *
     * @param SmsGatewayRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function store(SmsGatewayRequest $request) {

        $status  = 'error';
        $message = translate("Something went wrong");
        try {

            $gateway = $this->smsGatewayService->save($request);
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
     * @param SmsGatewayRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function update(SmsGatewayRequest $request) {

        $status  = 'error';
        $message = translate("Something went wrong");
        try {

            $gateway = $this->smsGatewayService->save($request);
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

            $notify = $this->smsGatewayService->statusUpdate($request);
            return $notify;

        } catch (ValidationException $validation) {

            return json_encode([
                
                'status'  => false,
                'message' => $validation->errors()
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
            $gateway->delete();
            
        } catch(\Exception $e) {

            $status  = 'success';
            $message = $e->getMessage();
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

            list($status, $message) = $this->bulkAction($request, 'is_default',[
                "model" => new Gateway(),
            ]);
    
        } catch (\Exception $exception) {

            $status  = 'error';
            $message = translate("Server Error: ").$exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }
}
