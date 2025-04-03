<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Carbon\Carbon; 
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use App\Http\Controllers\PaymentMethod\PaymentController;


class PaymentWithFlutterwave extends Controller
{
    
    public function callback($track, $type){

        if ($type == 'error') {
            $notify[] = ['error', 'Transaction Failed, Ref: ' . $track];
                    
        } else {

            if (isset($track)) {
                $paymentMethod = PaymentMethod::where('unique_code','FLUTTER107')->first();
                if(!$paymentMethod){
                    $notify[] = ['error', 'Invalid Payment gateway'];
                    return back()->withNotify($notify);
                }
                    
                $paymentTrackNumber = session()->get('payment_track');
                $paymentLog = PaymentLog::where('trx_number', $track)->first();
                $amount = round($paymentLog->final_amount, 2);

                $query = array(
                    "SECKEY" =>  $paymentMethod->payment_parameter->secret_key,
                    "txref" => $track
                );

                $data_string = json_encode($query);
                $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response);
                


                if ($response->data->status == "successful" && $response->data->chargecode == "00" && $amount == $response->data->amount && $paymentMethod->currency_code == $response->data->currency && $paymentMethod->status == '1') {
                        PaymentController::paymentUpdate($paymentLog->trx_number);
                        $notify[] = ['success', 'Transaction was successful, Ref: ' . $track];
                } else {
                    $notify[] = ['error', 'Unable to Process'];
                }
            } else {
                $notify[] = ['error', 'Unable to Process'];
            }

            return redirect()->route('user.dashboard')->withNotify($notify);
        }
    }
}
