<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
 
use App\Models\PaymentMethod;
use App\Models\PaymentLog;
use App\Http\Controllers\PaymentMethod\PaymentController;
use Illuminate\Support\Facades\Auth; 

class CoinbaseCommerce extends Controller
{
    public function store(Request $request)
    {
        $paymentMethod = PaymentMethod::where('unique_code','COINBASE108')->first();
        if(!$paymentMethod){
            $notify[] = ['error', 'Invalid Payment gateway'];
            return back()->withNotify($notify);
        }
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        $url = 'https://api.commerce.coinbase.com/charges';
        $array = [
            'name' =>auth()->user()->name,
            'description' => "Pay to " . site_settings("site_name"),
            'local_price' => [
                'amount' => round($paymentLog->final_amount, 2),
                'currency' => $paymentMethod->currency_code
            ],
            'metadata' => [
                'trx' => $paymentTrackNumber
            ],
            'pricing_type' => "fixed_price",
            'redirect_url' => route('user.callback.coinbase'),
            'cancel_url' => route('user.dashboard')
        ];

        $yourjson = json_encode($array);
        $ch = curl_init();
        $apiKey = $paymentMethod->payment_parameter->api_key;
        $header = array();
        $header[] = 'Content-Type: application/json';
        $header[] = 'X-CC-Api-Key: ' . "$apiKey";
        $header[] = 'X-CC-Version: 2018-03-22';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $yourjson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);


        $result = json_decode($result);

        if (@$result->error == '') {
            $send['redirect'] = true;
            $send['redirect_url'] = $result->data->hosted_url;
        } else {

            $send['error'] = true;
            $send['message'] = 'Some Problem Occured. Try Again';
        }

        $send['view'] = '';
        return json_encode(@$send);
    }

    public function confirmPayment(Request $request)
    {
        $paymentMethod = PaymentMethod::where('unique_code','COINBASE108')->first();
        if(!$paymentMethod){
            $notify[] = ['error', 'Invalid Payment gateway'];
            return back()->withNotify($notify);
        }

        $secret = $paymentMethod->payment_parameter->api_key;
        $headerName = 'X-CC-Api-Key';
        $headers = getallheaders();
        $signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
        $payload = trim(file_get_contents('php://input'));
        try {
            $sig = hash_hmac('sha256', $signraturHeader, $secret); 
            http_response_code(200);
             
        } catch (\Exception $exception) {
            http_response_code(400);
             
        }
    }
}
