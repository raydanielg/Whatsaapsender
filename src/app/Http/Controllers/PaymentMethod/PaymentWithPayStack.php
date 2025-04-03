<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PaymentLog;
use App\Http\Controllers\PaymentMethod\PaymentController;

class PaymentWithPayStack extends Controller
{

    public function store(Request $request)
    {
        $paymentMethod = PaymentMethod::where('unique_code','PAYSTACK103')->first();
        if(!$paymentMethod){
            $notify[] = ['error', 'Invalid Payment gateway'];
            return back()->withNotify($notify);
        }
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$request->reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$paymentMethod->payment_parameter->secret_key,
                "Cache-Control: no-cache",
            ),
        ));  
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);  
        if($response){
        	$data = json_decode($response, true);
        	if($data){
        		if($data['data'] && $data['data']['status'] == 'success'){
        			$amount = $data['data']['amount']/100;
                    $final_amount = round($paymentLog->final_amount, 2);
                    if ($amount == $final_amount && $data['data']['currency'] == $paymentMethod->currency_code  && $paymentLog->status == 0) {
	        			PaymentController::paymentUpdate($paymentLog->trx_number);
			            $notify[] = ['success', 'Payment successful!'];
			            return response()->json(['message' => 'Payment successfully done'], 200);
			        }else{
			        	$notify[] = ['error', 'Please valid amount paid'];
			        }
	        	}else{
	                $notify[] = ['error', $data['message']];
	        	}
        	}else{
        		$notify[] = ['error', 'Something went wrong try again'];
        	}
        }else{
        	$notify[] = ['error', 'Something went wrong try again'];
        }
    	return back()->withNotify($notify);
	}
}
