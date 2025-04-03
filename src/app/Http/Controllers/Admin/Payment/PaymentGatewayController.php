<?php

namespace App\Http\Controllers\Admin\Payment;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\AutomaticPaymentRequest;
use App\Http\Requests\ManualPaymentRequest;
use Illuminate\View\View;
use App\Service\Admin\Payment\PaymentGatewayService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class PaymentGatewayController extends Controller
{

    public $paymentGatewayService;
    
    public function __construct() {

        $this->paymentGatewayService = new PaymentGatewayService();
    }
    /**
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index() {
        
        Session::put("menu_active", true);
        $title           = translate("Payment Methods");
        $currencies      = json_decode(site_settings("currencies"), true);
        $payment_methods = PaymentMethod::routefilter()
                            ->search(['name'])
                            ->orderBy('id','ASC')
                            ->latest()
                            ->date()
                            ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                            ->appends(request()->all());
        return view('admin.payment.index', compact('title', 'payment_methods', 'currencies'));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        Session::put("menu_active", true);
        $title      = translate("Manual payment Method Create");
        $currencies = json_decode(site_settings("currencies"), true);
        return view('admin.manual_payment.create', compact('title', 'currencies'));
    }

    /**
     * 
     * @param ManualPaymentRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManualPaymentRequest $request) {

        $status  = 'error';
        $message = translate('Something went wrong');
        try {

            $this->paymentGatewayService->manualGatewayStore($request);
            $status  = 'success';
            $message = translate('Payment method has been created');

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function edit($id, $slug = null) {

        Session::put("menu_active", true);
        $title          = translate("Payment method update");
        $payment_method = PaymentMethod::findOrFail($id);
        $currencies     = json_decode(site_settings("currencies"), true);
        return view($slug ? 'admin.payment.edit' : 'admin.manual_payment.edit', compact('title', 'payment_method', 'currencies'));
    }

    /**
     *
     * @param AutomaticPaymentRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function automaticUpdate(AutomaticPaymentRequest $request, $id) {

        $status  = 'error';
        $message = translate('Something went wrong');
        try {

            $this->paymentGatewayService->automaticGatewayUpdate($request, $id);
            
            $status  = 'success';
            $message = translate('Payment method has been updated');

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     *
     * @param ManualPaymentRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function manualUpdate(ManualPaymentRequest $request, $id) {

        $status  = 'error';
        $message = translate('Something went wrong');
        try {
            
            $this->paymentGatewayService->manualGatewayUpdate($request, $id);
            $status  = 'success';
            $message = translate('Payment method has been updated');

        } catch (\Exception $e) {

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
    public function statusUpdate(Request $request) {
        
        try {
            $this->validate($request,[

                'id'     => 'required',
                'value'  => 'required',
                'column' => 'required',
            ]); 

            $notify = $this->paymentGatewayService->statusUpdate($request);
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
     * @param Request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request) {

        $this->validate($request, [
            'id' => 'required'
        ]);
        $manual_method = PaymentMethod::findOrFail($request->id);
        $filePath = config('setting.file_path.manual_payment.path')."/".$manual_method->image;
        
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        
        $manual_method->delete();
        $notify[] = ['success', "Manual Payment Method Removed Successfully"];
        return back()->withNotify($notify);
    }
}
