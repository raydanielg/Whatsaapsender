<?php

namespace App\Http\Controllers\User\Gateway;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhatsappCloudApiRequest;
use App\Models\WhatsappDevice;
use App\Service\Admin\Gateway\WhatsappGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Type\NullType;

class WhatsappCloudApiController extends Controller
{
    public $whatsappGatewayService;
    
    public function __construct() {

        $this->whatsappGatewayService = new WhatsappGatewayService();
    }

    /**
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index($id = null) {
        
        Session::put("menu_active", false);
    	$title       = translate("WhatsApp Cloud API List");
        $credentials = config('setting.whatsapp_business_credentials');
        $user        = auth()->user();
        $gateways    = $id ? WhatsappDevice::where('id', $id)
                                                ->where("user_id", auth()->user()->id)
                                                ->where("type", StatusEnum::TRUE->status())
                                                ->search(['name'])
                                                ->latest()
                                                ->routefilter()
                                                ->paginate(paginateNumber(site_settings("paginate_number")))
                                                ->appends(request()->all()) :
                            WhatsappDevice::where('user_id', auth()->user()->id)
                                            ->where("type", StatusEnum::TRUE->status())
                                            ->search(['name'])
                                            ->latest()
                                            ->routefilter()
                                            ->paginate(paginateNumber(site_settings("paginate_number")))
                                            ->appends(request()->all());
        
    	return view('user.gateway.whatsapp.cloud_api', compact('title', 'gateways', 'credentials', 'user'));
    }

    /**
     *
     * @param WhatsappDeviceRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function save(WhatsappCloudApiRequest $request) {
        
        $status  = 'success';
        $message = translate("Whatsapp Business account has been saved");

        try {

            $this->whatsappGatewayService->save($request, auth()->user()->id);

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

    public function webhook(Request $request) {

        $user = Auth::user();
        $user->webhook_token = $request->input("verify_token");
        $user->save();
        $notify[] = ['success', translate('Whatsapp Cloud Webhook verify token saved successfully')];
        return back()->withNotify($notify);
    }
}
