<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\PaymentMethod;
use App\Models\PaymentLog;
use App\Http\Controllers\PaymentMethod\PaymentController;
use Session;

class PaymentWithStripe extends Controller
{

    public function __construct()
    {
        
    }
    public function stripePost(Request $request)
    {
    	$paymentMethod = PaymentMethod::where('unique_code','STRIPE101')->first();
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        $amount = round($paymentLog->final_amount, 2) * 100;
    
        \Stripe\Stripe::setApiKey(@$paymentMethod->payment_parameter->secret_key);
            
            try {
            
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' =>   $amount,
                    'currency' => $paymentMethod->currency_code,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],
                ]);

                $output = [
                    'clientSecret' => $paymentIntent->client_secret,
                ];
                return  ($output);
            } catch (Error $e) {
                http_response_code(500);
                return  json_encode(['error' => $e->getMessage()]);
            }


    
    }

    public function success(){

        if(request()->redirect_status == 'succeeded') {
            $paymentTrackNumber = session()->get('payment_track');
            $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
            PaymentController::paymentUpdate($paymentLog->trx_number);
            $notify[] = ['success', 'Payment successful!'];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }
        $notify[] = ['error', 'Payment Failed!'];
        return redirect()->route('user.dashboard')->withNotify($notify);
        
    }
   
}
