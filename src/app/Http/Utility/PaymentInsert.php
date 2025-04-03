<?php
namespace App\Http\Utility;

use App\Enums\StatusEnum;
use App\Models\PaymentMethod;
use App\Models\PaymentLog;
use App\Models\Subscription;

class PaymentInsert
{
    public static function paymentCreate($gatewayId) {
        $paymentMethod = PaymentMethod::where('unique_code', $gatewayId)->where('status', StatusEnum::TRUE->status())->first();
        
        if (!$paymentMethod) {
            
            return response()->json([
                'status' => 'error',
                'message' => translate("Could Not Find Payment Gateway")
            ]);
        }
       
        $userId       = auth()->user()->id;
        $subscription = Subscription::where('id', session('subscription_id'))
            ->where('user_id', $userId)
            ->whereIn('status',[0,1,2,3])
            ->first();
        
        $charge       = ($subscription->amount * $paymentMethod->percent_charge / 100);
        $amount       = $subscription->amount;
        $final_amount = ($amount+$charge) * $paymentMethod->rate;
        $paymentLog   = PaymentLog::create([

            'subscriptions_id' => $subscription->id,
            'user_id'          => $userId,
            'method_id'        => $paymentMethod->id,
            'charge'           => $charge,
            'rate'             => $paymentMethod->rate,
            'amount'           => $amount,
            'final_amount'     => $final_amount,
            'trx_number'       => trxNumber(),
            'status'           => 0,
        ]);
        session()->put('payment_track', $paymentLog->trx_number);

        return $paymentLog;
    }
}



