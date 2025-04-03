<?php

namespace App\Http\Controllers\Admin\Gateway;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhatsappDeviceRequest;
use App\Http\Requests\WhatsappServerRequest;
use App\Models\WhatsappDevice;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Service\Admin\Gateway\WhatsappGatewayService;
use Exception;

class WhatsappDeviceController extends Controller {

    public $whatsappGatewayService;
    
    public function __construct() {

        $this->whatsappGatewayService = new WhatsappGatewayService();
    }

    /**
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index() {
        
        Session::put("menu_active", true);
    	$title       = translate("WhatsApp Device List");
        $gateways    = WhatsappDevice::where('admin_id', auth()->guard('admin')->user()->id)
                                        ->where("type", StatusEnum::FALSE->status())
                                        ->search(['name'])
                                        ->latest()
                                        ->routefilter()
                                        ->paginate(paginateNumber(site_settings("paginate_number")))
                                        ->appends(request()->all());

        $server_status = $this->whatsappGatewayService->checkServerStatus();
        
    	return view('admin.gateway.whatsapp.device', compact('title', 'gateways', 'server_status'));
    }

   /**
     *
     * @param WhatsappDeviceRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function save(WhatsappDeviceRequest $request) {
        
        $status  = 'success';
        $message = translate("WhatsApp device has been saved");

        try {

            $this->whatsappGatewayService->save($request);

        } catch (\Exception $e) {

            $status  = 'error';
            $message = translate("Server Error: ") . $e->getMessage();
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
    public function delete(Request $request): mixed {
        
        try {

            $notify = $this->whatsappGatewayService->delete($request);

        } catch (\Exception $e) {

            $notify[] = ['error',translate("Server Error: ") . $e->getMessage()];
        }
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws Exception.
     * 
     */
    public function statusUpdate(Request $request): bool|string {

        $message = translate( 'Opps! Something went wrong, try again');
        try {
           
            $message = $this->whatsappGatewayService->deviceStatusUpdate($request, auth()->guard('admin')->user()->id);

        }catch (\Exception $exception) {

            $message = $exception->getMessage();
        }
        return json_encode([

            'success' => $message
        ]);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws Exception.
     * 
     */
    public function whatsappQRGenerate(Request $request): bool|string {
        
        $data = [];
        
        try {

            $whatsapp = WhatsappDevice::where('id', $request->input('id'))->first();
            list($response, $responseBody) = $this->whatsappGatewayService->sessionCreate($whatsapp);
            
            if ($response->status() === 200) {

                $data['status']  = $response->status();
                $data['qr']      = $responseBody->data->qr;
                $data['message'] = $responseBody->message;
    
            } else {
                
                $msg = $response->status() === 500 ? "Invalid Software License" : $responseBody->message;
                $data['status']  = $response->status();
                $data['qr']      = '';
                $data['message'] = $msg;
            }
    
            $response = [
                'response' => $whatsapp,
                'data' => $data
            ];

        } catch (\Exception $exception) {

            $response = $exception->getMessage();
        }
        
        return json_encode($response);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws Exception.
     * 
     */
    public function getDeviceStatus(Request $request): bool|string {

        $whatsapp = WhatsappDevice::where('admin_id',auth()->guard('admin')->user()->id)->where('id', $request->input('id'))->first();
        $credentials = $whatsapp->credentials;
        $data = [];
        
        try {
            $checkConnection = $this->whatsappGatewayService->sessionStatus($whatsapp->name);

            if ($whatsapp->status == "connected" || $checkConnection->status() === 200) {

                $whatsapp->status = 'connected';
                $response = json_decode($checkConnection->body());

                if (isset($response->data->wpInfo)) {

                    $wpNumber = str_replace('@s.whatsapp.net', '', $response->data->wpInfo->id);
                    $wpNumber = explode(':', $wpNumber);
                    $wpNumber = $wpNumber[0] ?? $whatsapp->credentials["number"];
                    $credentials["number"] = $wpNumber;
                    $whatsapp->credentials = $credentials;

                }
                
                $whatsapp->save();
                $data['status']  = 301;
                $data['qr']      = asset('assets/file/dashboard/image/done.gif');
                $data['message'] = 'Successfully connected WhatsApp device';
            }
        } catch (\Exception $e) {
            $data['status'] = $e->getCode();
            $data['qr'] = '';
            $data['message'] = $e->getMessage();
        }

        $response = [
            'response' => $whatsapp,
            'data' => $data
        ];
        return json_encode($response);
    }

    /**
     *
     * @param WhatsappServerRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */

    public function updateServer(WhatsappServerRequest $request) {
        
        $status  = "success";
        $message = translate("Server configuration updated successfully");
        try {

            $updated_env = $this->whatsappGatewayService->updateEnvParam($request);

            $path = app()->environmentFilePath();
    
            foreach ($updated_env as $key => $value) {
    
                $escaped = preg_quote('='.env($key), '/');
                
                file_put_contents($path, preg_replace(
                    "/^{$key}{$escaped}/m",
                    "{$key}={$value}",
                    file_get_contents($path)
                ));
    
            }
        } catch(\Exception $e) {

            $status  = 'error';
            $message = translate("Server Error");
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }
}
