<?php
namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PaymentMethod;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PaymentMethod\PaymentController;
use App\Library\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $paymentMethod = PaymentMethod::where('unique_code','SSLCOMMERZ104')->first();
       
        if(!$paymentMethod){
            $notify[] = ['error', 'Invalid Payment gateway'];
            return back()->withNotify($notify);
        }
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();

        $post_data = array();
        
        $post_data['total_amount'] = shortAmount($paymentLog->final_amount); 
        $post_data['currency'] = $paymentMethod->currency_code;
        $post_data['tran_id'] = $paymentLog->trx_number; //tran_id must be unique
        
        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->name ?? "demo user";
        $post_data['cus_email'] = $user->email ?? "demo email";
        $post_data['cus_add1'] = $user->address ?? "Address";
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = @$user->phone ?? "0000000000";
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Shipping";
        $post_data['ship_add1'] = "address 1";
        $post_data['ship_add2'] = "address 2";
        $post_data['ship_city'] = "City";
        $post_data['ship_state'] = "State";
        $post_data['ship_postcode'] = "ZIP";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        $sslc = new SslCommerzNotification();
        
        $payment_options = $sslc->makePayment($post_data, 'hosted');
        
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $sslc = new SslCommerzNotification();
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $paymentLog = PaymentLog::where('trx_number', $tran_id)->first();
        $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
        if($validation){
            if ($paymentLog->status == 0){
                PaymentController::paymentUpdate($paymentLog->trx_number);
                $notify[] = ['success', 'Payment successful!'];
                return redirect()->route('user.dashboard')->withNotify($notify);
            }
        }
        else{
            $notify[] = ['error', 'Transaction is Invalid'];
            return back()->withNotify($notify);
        }
    }
 

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $paymentLog = PaymentLog::where('trx_number', $tran_id)->first();

        if ($paymentLog->status == 0) {
            $paymentLog->status = 3;
            $paymentLog->save();
            $notify[] = ['error', 'Transaction is Falied'];
        } else if ($paymentLog->status == 2) {
            $notify[] = ['error', 'Transaction is already Successful'];
        } else {
           $notify[] = ['error', 'Transaction is Invalid'];
        }
        return back()->withNotify($notify);
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $paymentLog = PaymentLog::where('trx_number', $tran_id)->first();
        if($paymentLog->status == 0) {
            $paymentLog->status = 3;
            $paymentLog->save();
            $notify[] = ['error', 'Transaction is Cancel'];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }else if($paymentLog->status == 2) {
            $notify[] = ['error', 'Transaction is already Successful'];
        }else{
            $notify[] = ['error', 'Transaction is Invalid'];
        }
        return redirect()->route('user.dashboard')->withNotify($notify);
    }


}

