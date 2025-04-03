<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentMethod\PaymentController;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Session;
use Razorpay\Api\Api;

class PaymentWithRazorpay extends Controller
{
    public function ipn(Request $request)
    { 
      
        $success = true;
        $error = "Payment Failed";
        $paymentMethod = PaymentMethod::where('unique_code','RAZOR107')->first();
        if(!$paymentMethod){
            $notify[] = ['error', 'Invalid Payment gateway'];
            return back()->withNotify($notify);
        }
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        
        $api = new Api($paymentMethod->payment_parameter->key_id, $paymentMethod->payment_parameter->key_secret);

        try
        { 
            $attributes = array(
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );

            $api->utility->verifyPaymentSignature($attributes);
        }
        catch(SignatureVerificationError $e)
        {
            $success = false;
            $notify[] = ['error', 'Razorpay Error : ' . $e->getMessage()]; 
        }

        if ($success === true)
        {
            $notify[] = ['success', 'Your payment was successful, Ref: ' . $paymentTrackNumber];
            PaymentController::paymentUpdate($paymentLog->trx_number);
        }
        else
        {
            $html = "<p>Your payment failed</p>
                     <p>{$error}</p>";
            $notify[] = ['error', $html];
        }

        return redirect()->route('user.dashboard')->withNotify($notify);
    }
}
