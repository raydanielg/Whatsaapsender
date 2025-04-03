<?php

namespace App\Http\Controllers\User\Gateway;

use App\Enums\AndroidApiSimEnum;
use App\Enums\StatusEnum;
use Illuminate\View\View;
use App\Models\AndroidApi;
use App\Traits\ModelAction;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\AndroidApiSimInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Admin\AndroidApiRequest;
use App\Models\Gateway;
use Illuminate\Validation\ValidationException;
use App\Service\Admin\Gateway\AndroidApiService;

class AndroidApiController extends Controller
{
	public $androidApiService;
    public function __construct() {

        $this->androidApiService = new AndroidApiService();
    }

	/**
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index(): View {

		Session::put("menu_active", false);
    	$title 	  = translate("Android Gateway List");
        $user = auth()->user();
        $allowed_access = (object)planAccess($user);
        if($allowed_access) {
            $allowed_access   = (object)planAccess($user);
        } else {
            $notify[] = ['error', translate('Please Purchase A Plan')];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }
    	$androids = $allowed_access->type == StatusEnum::FALSE->status() ? 
                        AndroidApi::where('user_id', auth()->user()->id)
                                    ->search(['name'])
                                    ->latest()
                                    ->routefilter()
                                    ->paginate(paginateNumber(site_settings("paginate_number")))
                                    ->appends(request()->all()):
                        AndroidApi::whereNull('user_id')
                                    ->search(['name'])
                                    ->latest()
                                    ->routefilter()
                                    ->paginate(paginateNumber(site_settings("paginate_number")))
                                    ->appends(request()->all());


    	return view('user.gateway.sms.android.index', compact('title', 'androids', 'allowed_access', 'user'));
    }

	/**
     *
     * @param AndroidApiRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function store(AndroidApiRequest $request) {
		
		$status  = 'success';
        $message = translate("A new android gateway has been added");

        try {
            
            $plan_access          = (object) planAccess(auth()->user());
            $android_access       = (object) $plan_access->android;
            $android_device_count = AndroidApi::where("user_id", auth()->user()->id)->where('status', AndroidApiSimEnum::ACTIVE)->get()->count();
            
            if($android_access->is_allowed && ($android_device_count < $android_access->gateway_limit || $android_access->gateway_limit == 0)) { 

                $this->androidApiService->save($request, auth()->user()->id);
            } else {

                $status = 'error';
                $message = translate("Current plan doesnt allow you to create anymore Android Gateways");
            }
           
        } catch (\Exception $e) {

            $status  = 'error';
            $message = translate("Server Error: ") . $e->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

	/**
     *
     * @param AndroidApiRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function update(AndroidApiRequest $request) {

    	$status  = 'success';
        $message = translate("A new android gateway has been added");

        try {
            $plan_access          = (object) planAccess(auth()->user());
            $android_access       = (object) $plan_access->android;
            $android_device_count = AndroidApi::where("user_id", auth()->user()->id)->where('status', AndroidApiSimEnum::ACTIVE)->get()->count();

             if($android_access->is_allowed && ($android_device_count < $android_access->gateway_limit || $android_access->gateway_limit == 0)) { 

                $this->androidApiService->save($request);
            } else {

                $status = 'error';
                $message = translate("Current plan doesnt allow you to create anymore Android Gateways");
            }

        } catch (\Exception $e) {

            $status  = 'error';
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
            $notify   = [];
            $plan_access = (object) planAccess(auth()->user());
            $android_access = (object) $plan_access->android;
            $android_device_count = AndroidApi::where("user_id", auth()->user()->id)
                ->where("status", StatusEnum::TRUE->status())
                ->get()
                ->count();
            $android_api = AndroidApi::where("id",$request->input('id'))->first();
            if($android_api->status == StatusEnum::TRUE->status() || ($android_access->is_allowed && ($android_device_count < $android_access->gateway_limit || $android_access->gateway_limit == -1))) { 
                
                $notify = $this->androidApiService->statusUpdate($request);
                
            } else {

                $notify = json_encode([
                    'reload'  => true,
                    'status'  => false,
                    'message' => translate("Plan Doesnt allow you to enable more that ").$android_access->gateway_limit. translate(" Android Gateways")
                ]);
            }
            
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
	 * @param int $id
	 * 
     * @return \Illuminate\View\View
     * 
     */
    public function simList(int $id = null): View {
        
        Session::put("menu_active", false);
    	$android  = AndroidApi::where('user_id', auth()->user()->id)->firstOrFail();
    	$title    = ucfirst(textFormat(['_'],$android->name,' '))." - Sim List ";
    	$sims     = AndroidApiSimInfo::where('android_gateway_id', $id)
										->latest()
                                        ->date()
                                        ->with('androidGateway')
                                        ->search(['sim_number'])
                                        ->filter(['status'])
										->paginate(paginateNumber(site_settings("paginate_number")));
    	return view('user.gateway.sms.android.sim', compact('title', 'android', 'sims', 'id'));
    }
	
	 /**
     *
     * @param Request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request) {
		
		$status  = 'success';
		$message = translate("Deleted android gateway successfully");
        try {

			$android = AndroidApi::where('user_id', auth()->user()->id)
								->where('id', $request->input('id'))
								->firstOrFail();
			$android->simInfo()->delete();
			$android->delete();
			
		} catch(\Exception $e) {

			$status  = 'error';
            $message = translate("Server Error: ") . $e->getMessage();
		}
		$notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

	/**
     *
     * @param Request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function simNumberDelete(Request $request) {

		$status  = 'success';
		$message = translate("Deleted android gateway successfully");
        try {

			AndroidApiSimInfo::where('id', $request->id)->delete();

		} catch(\Exception $e) {
			
			$status  = 'error';
            $message = translate("Server Error: ") . $e->getMessage();
		}
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

	public function linkStore(Request $request) {

		$request->validate([
			'app_link'     => ['required', 'url'],
		]);

		$general = GeneralSetting::first();
		$general->app_link = $request->input("app_link");
		$general->save();
		$notify[] = ['success', 'Apk file link added'];
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

            list($status, $message) = $this->androidApiService->bulkAction($request, [
                "model" => new AndroidApi(),
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
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function simBulk(Request $request) :RedirectResponse {

        $status  = 'success';
        $message = translate("Successfully Performed bulk action");
        try {

            list($status, $message) = $this->androidApiService->simBulkAction($request, [
                "model" => new AndroidApiSimInfo(),
            ]);
    
        } catch (\Exception $exception) {

            $status  = 'error';
            $message = translate("Server Error: ").$exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }
}
