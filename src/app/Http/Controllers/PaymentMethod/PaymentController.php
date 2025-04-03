<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Enums\PaymentStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use App\Models\GeneralSetting;
use App\Models\Subscription;
use App\Models\User;
use App\Models\CreditLog;
use App\Models\EmailCreditLog;
use App\Models\Transaction;
use App\Http\Utility\SendMail;
use App\Models\AndroidApi;
use App\Models\Currency;
use Illuminate\Support\Facades\Session;
use App\Models\WhatsappCreditLog;
use Razorpay\Api\Api;
use App\Models\Gateway;
use App\Models\WhatsappDevice;
use App\Service\Admin\Core\FileService;
use App\Service\Admin\Gateway\WhatsappGatewayService;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public $fileService;
    public function __construct() {

        $this->fileService = new FileService();
    }
    public function preview()
    {
        $title = translate("Payment Info");
        $userId = auth()->user()->id;
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        $subscription = Subscription::where('id', session('subscription_id'))->where('user_id', $userId)->whereIn('status',[0,1,2])->first();
        return view('user.payment', compact('title', 'paymentLog','subscription'));
    }

    public function paymentConfirm($id = null) {
        
        Session::put("menu_active", false);
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog         = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        $paymentMethod      = PaymentMethod::where('unique_code', $paymentLog->paymentGateway->unique_code)->first();
      
        if(!$paymentMethod) {

            $notify[] = ['error', 'Invalid Payment gateway'];
            return back()->withNotify($notify);
        }
        if($paymentLog->paymentGateway->unique_code == "STRIPE101"){
            $title = translate("Payment with Stripe");
            return view('user.payment.strip', compact('title', 'paymentMethod', 'id'));

        } else if($paymentLog->paymentGateway->unique_code == "PAYPAL102") {

            $title = translate("Payment with PayPal");
            $data = [
                "client_id"   => $paymentMethod->payment_parameter->client_id,
                "description" => "Payment With Paypal",
                "custom_id"   => $paymentTrackNumber,
                "amount"      => round($paymentLog->amount),
                "currency"    => $paymentLog->paymentGateway->currency_code,
                "unique_code"    => $paymentLog->paymentGateway->unique_code,
            ];
            $data = (object) $data;
            return view('user.payment.paypal', compact('title', 'paymentMethod', 'paymentLog', 'paymentTrackNumber', 'data', 'id'));

        }else if($paymentLog->paymentGateway->unique_code == "PAYSTACK103"){
            $title = translate("Payment with Paystack");
            return view('user.payment.paystack', compact('title', 'paymentMethod', 'paymentLog', 'id'));
        }else if($paymentLog->paymentGateway->unique_code == "SSLCOMMERZ104"){
            $title = translate("Payment with SSLcommerz");
            return view('user.payment.sslcommerz', compact('title', 'paymentMethod', 'paymentLog', 'id'));
        }else if($paymentLog->paymentGateway->unique_code == "PAYTM105"){
            $title = translate("Payment with Paytm");
            return view('user.payment.paytm', compact('title', 'paymentMethod', 'paymentLog'));
        }else if($paymentLog->paymentGateway->unique_code == "INSTA106"){
            $title = translate("Payment with Instamojo");
            return view('user.payment.instamojo', compact('title', 'paymentMethod', 'paymentLog', 'id'));
        }else if($paymentLog->paymentGateway->unique_code == "FLUTTER107"){
            $title = translate("Payment with Flutterwave");
            return view('user.payment.flutterwave', compact('title', 'paymentMethod', 'paymentLog', 'id'));
        }else if($paymentLog->paymentGateway->unique_code == "COINBASE108"){
            $title = translate("Payment with Coinbase Commerce");
            return view('user.payment.coinbase', compact('title', 'paymentMethod', 'paymentLog', 'id'));
        }else if($paymentLog->paymentGateway->unique_code == "RAZOR107"){
            $title = translate("Payment with Razor Pay");
            $api = new Api($paymentMethod->payment_parameter->key_id, $paymentMethod->payment_parameter->key_secret);
            
            try {
                $order = $api->order->create(
                    array(
                        'receipt' => $paymentTrackNumber,
                        'amount' => round(($paymentLog->final_amount)*100),
                        'currency' => $paymentMethod->currency_code,
                        'payment_capture' => '1'
                    )
                );
            } catch (\Exception $e) {

                $notify[] = ['error', translate("Please contact admin: ").$e->getMessage()];
                return redirect()->route('user.dashboard')->withNotify($notify);
            }
           
            return view('user.payment.razorpay', compact('title', 'paymentMethod', 'paymentLog','order'));
        }else if($paymentLog->paymentGateway->unique_code == "BKASH109"){
            $title = translate("Payment with Bkash");
            return view('user.payment.bkash', compact('title', 'paymentMethod', 'paymentLog', 'id'));
        }else{
            return redirect()->route('user.dashboard');
        }
    }

    public static function paymentUpdate($trx)
    {
        $paymentData = PaymentLog::where('trx_number', $trx)->first();
     
        if($paymentData && $paymentData->status == 0) {

            $paymentData->status = 2;
            $paymentData->save();

            $subscription = Subscription::where('id', $paymentData->subscriptions_id)->first();
            $last_expired_plan = Subscription::where("status", Subscription::EXPIRED)->latest()->first();
            $last_renewed_plan = Subscription::where("status", Subscription::RENEWED)->latest()->first();

            if( $last_expired_plan && $last_expired_plan?->plan_id == $subscription->plan_id) {
                Subscription::where(["plan_id" => $subscription->plan_id, "status" => Subscription::EXPIRED])->delete();
                $subscription->status = Subscription::RENEWED;
              	
            } elseif($subscription) {
              	
                $subscription->status = Subscription::RUNNING;
                if($last_renewed_plan) {
                    Subscription::where("status", Subscription::RENEWED)->update(["status" => Subscription::INACTIVE]);
                }
                if($last_expired_plan) {
                    Subscription::where("status", Subscription::EXPIRED)->update(["status" => Subscription::INACTIVE]);
                }
            } else {
          		$subscription->status        = Subscription::RUNNING;
            }
            Subscription::where('user_id', $paymentData->user_id)->where('status', 1)->delete();
            AndroidApi::where(["user_id" => $paymentData->user_id, "status" => StatusEnum::TRUE->status()])->update(["status" => StatusEnum::FALSE->status()]);
            $whatsapp_devices = WhatsappDevice::where(["user_id" => $paymentData->user_id, "status" => "connected"])->get();
            if(count($whatsapp_devices) > 0) {

                $whatsappGatewayService = new WhatsappGatewayService;
            	foreach($whatsapp_devices as $whatsapp_device) {

                    $whatsapp_device->status = StatusEnum::FALSE->status();
                    $whatsappGatewayService->sessionDelete($whatsapp_device->name);
                    $whatsapp_device->update();
                }
            }
            $subscription->plan_id       = $subscription->plan->id;
            $subscription->amount        = $subscription->plan->amount;
            $subscription->expired_date  = $subscription->expired_date->addDays($subscription->plan->duration);
            $subscription->save();
            $previousSubs = Subscription::where('user_id', $paymentData->user_id)->where('status', 3)->pluck('id');
            if ($previousSubs->isNotEmpty()) {
                Subscription::destroy($previousSubs->toArray());
            } 
            PaymentLog::where('user_id', $paymentData->user_id)->where('status', 1)->update(['status' => 3, 'feedback' => "Transaction Process Did Not Complete!"]);
            $user = User::find($paymentData->user_id);
        
            if($subscription->status == Subscription::RENEWED && $subscription->plan->carry_forward == 1) {
                $user->sms_credit += $subscription->plan->sms->credits;
                $user->email_credit += $subscription->plan->email->credits;
                $user->whatsapp_credit += $subscription->plan->whatsapp->credits;
            } else {
                
                $user->sms_credit = $subscription->plan->sms->credits;
                $user->email_credit = $subscription->plan->email->credits;
                $user->whatsapp_credit = $subscription->plan->whatsapp->credits;
            }
           
            Gateway::where('user_id', $user->id)->update(['status' => 0, 'is_default' => 0]);
            $user->save();
            Transaction::create([
                'user_id'            => $user->id,
                'payment_method_id'  => $paymentData->method_id,
                'amount'             => $paymentData->amount,
                'transaction_type'   => Transaction::PLUS,
                'transaction_number' => $paymentData->trx_number,
                'details'            => 'Enrollment Confirmed:'.$subscription->plan->name.' Plan Subscribed!',
            ]);

            $mailCode = [
                'trx'             => $paymentData->trx_number,
                'amount'          => shortAmount($paymentData->final_amount),
                'charge'          => shortAmount($paymentData->charge),
                'currency'        => getDefaultCurrencySymbol(json_decode(site_settings("currencies"), true)),
                'rate'            => shortAmount($paymentData->rate),
                'method_name'     => $paymentData->paymentGateway->name,
                'method_currency' => $paymentData->paymentGateway->currency_code,
                'name'            => $user->name,
                'time'            => Carbon::now()
            ];
            SendMail::MailNotification($user,'PAYMENT_CONFIRMED',$mailCode);
        }
    }


    public function manualPayment()
    {
        $paymentTrackNumber = session()->get('payment_track');
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        if(!$paymentLog){
            return redirect()->route('user.dashboard');
        }
        $title = translate('Payment Confirm');
        $paymentMethod =  $paymentLog->paymentGateway;
        return view('user.payment.manual_confirm', compact('paymentLog', 'title', 'paymentMethod'));
    }

    public function manualPaymentUpdate(Request $request) {

        $paymentTrackNumber = session()->get('payment_track');
        $mime_types = implode(',', json_decode(site_settings("mime_types"), true));
        $paymentLog = PaymentLog::where('trx_number', $paymentTrackNumber)->first();
        if(!$paymentLog){
            return redirect()->route('user.dashboard');
        }
        $rules = array();
        if($paymentLog->paymentGateway->payment_parameter != null){
            foreach($paymentLog->paymentGateway->payment_parameter as $key => $value){
                if($key!="0") {
                    $rules[$key] = ['required'];
                    if($value->field_type == 'file'){
                        array_push($rules[$key], 'image');
                        array_push($rules[$key], "mimes:$mime_types");
                        array_push($rules[$key], 'max:2048');
                    }
                    elseif($value->field_type == 'text'){
                        array_push($rules[$key], 'max:191');
                    }
                    elseif($value->field_type == 'textarea'){
                        array_push($rules[$key], 'max:10000');
                    }
                }
            }
        }
        $this->validate($request, $rules);
        $path       = filePath()['payment_file']['path'];
        $collection = collect($request);
        $userData = [];
        if ($paymentLog->paymentGateway->payment_parameter != "" || !empty($paymentLog->paymentGateway->payment_parameter)) {
            foreach ($collection as $k => $v) {
                foreach ($paymentLog->paymentGateway->payment_parameter as $inKey => $inVal){
                    if ($inKey <= 0) {
                        continue;
                    } else {
                        if ($inVal->field_type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $userData[$inKey] = [
                                        'field_name' => $this->fileService->uploadFIle($request[$inKey], null, $path, null, false) ? $this->fileService->uploadFIle($request[$inKey], null, $path, null, false) :"",
                                        'field_type' => $inVal->field_type,
                                    ];
                                }catch(\Exception $exp) {
                                    
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $userData[$inKey] = [
                                'field_name' =>$request[$inKey],
                                'field_type' => $inVal->field_type,
                            ];
                        }
                    }
                }
            }
        }
        $paymentLog->user_data = $userData;
        $paymentLog->status = PaymentStatusEnum::PENDING->value;
        $paymentLog->save();
        $notify[] = ['success', 'Your order request has been taken.'];
        return redirect()->route('user.dashboard')->withNotify($notify);
    }
}
