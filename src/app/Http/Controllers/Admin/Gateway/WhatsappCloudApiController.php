<?php

namespace App\Http\Controllers\Admin\Gateway;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhatsappCloudApiRequest;
use App\Models\WhatsappDevice;
use App\Service\Admin\Gateway\WhatsappGatewayService;
use Illuminate\Http\Request;
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
        
        Session::put("menu_active", true);
    	$title       = translate("WhatsApp Cloud API List");
        $credentials = config('setting.whatsapp_business_credentials');
        $gateways    = $id ? WhatsappDevice::where('id', $id)
                                                ->where("type", StatusEnum::TRUE->status())
                                                ->search(['name'])
                                                ->latest()
                                                ->routefilter()
                                                ->paginate(paginateNumber(site_settings("paginate_number")))
                                                ->appends(request()->all()) :
                            WhatsappDevice::where('admin_id', auth()->guard('admin')->user()->id)
                                            ->where("type", StatusEnum::TRUE->status())
                                            ->search(['name'])
                                            ->latest()
                                            ->routefilter()
                                            ->paginate(paginateNumber(site_settings("paginate_number")))
                                            ->appends(request()->all());
        
    	return view('admin.gateway.whatsapp.cloud_api', compact('title', 'gateways', 'credentials'));
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
}
