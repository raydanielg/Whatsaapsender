<?php

namespace App\Http\Controllers\Admin\Gateway;

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

		Session::put("menu_active", true);
    	$title 	  = translate("Android Gateway List");
    	$androids = AndroidApi::where('admin_id', auth()->guard('admin')->user()->id)
									->search(['name'])
									->latest()
									->routefilter()
									->paginate(paginateNumber(site_settings("paginate_number")))
									->appends(request()->all());

    	return view('admin.gateway.sms.android.index', compact('title', 'androids'));
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

            $this->androidApiService->save($request);

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

            $this->androidApiService->save($request);

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

            $notify = $this->androidApiService->statusUpdate($request);
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
        
        Session::put("menu_active", true);
    	$android  = AndroidApi::where('admin_id', auth()->guard('admin')->user()->id)->firstOrFail();
    	$title    = ucfirst(textFormat(['_'],$android->name,' '))." - Sim List ";
    	$sims     = AndroidApiSimInfo::where('android_gateway_id', $id)
										->latest()
                                        ->date()
                                        ->with('androidGateway')
                                        ->search(['sim_number'])
                                        ->filter(['status'])
										->paginate(paginateNumber(site_settings("paginate_number")));
    	return view('admin.gateway.sms.android.sim', compact('title', 'android', 'sims', 'id'));
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

			$android = AndroidApi::where('admin_id', auth()->guard('admin')->user()->id)
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
