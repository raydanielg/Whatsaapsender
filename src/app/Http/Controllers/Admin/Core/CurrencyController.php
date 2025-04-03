<?php

namespace App\Http\Controllers\Admin\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Core\SettingService;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Service\Admin\Core\CollectionService;

class CurrencyController extends Controller
{
    public SettingService $settingService;
    public function __construct(SettingService $settingService) { 

        $this->settingService = $settingService;
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index() {
        
        Session::put("menu_active", true);
        $title      = translate("Manage currencies");
        $countries  = json_decode(file_get_contents(resource_path(config('constants.options.country_code')) . 'countries.json'),true);
        $currencies = new CollectionService(collect(json_decode(site_settings('currencies'), true)));
        $currencies = $currencies->collectionSearch(['name', 'symbol', 'rate', 'status'])
                        ->keyFilter(last(explode('.', request()->route()->getName())))
                        ->paginate(paginateNumber(site_settings("paginate_number")));
        return view('admin.setting.currency', compact('title', 'currencies', 'countries'));
    }

    /**
     *
     * @param CurrencyRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function save(CurrencyRequest $request) {

        $status  = 'success';
        $message = translate("Currency has been saved");
        try {
            
            $data = $this->settingService->prepData($request);
            $this->settingService->updateSettings($data);

        } catch (\Exception $e) {
            
            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     */
    public function statusUpdate(Request $request) {
        
        $status  = false;
        $reload  = false;
        $message = "Something went wrong";
        try {
            
            $this->validate($request,[

                'id'     => 'required',
                'column' => 'required',
            ]);

            list($status, $reload, $message, $data) = $this->settingService->statusUpdate($request);
            
            $this->settingService->updateSettings($data);
            return json_encode([
                'reload'  => $reload,
                'status'  => $status,
                'message' => $message
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $validation) {

                $status  = false;
                $message = $validation->errors();

        } catch (\Exception $error) {

            $status = false;
            $message = $error->getMessage();
        }
        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message
        ]);
    }
}
