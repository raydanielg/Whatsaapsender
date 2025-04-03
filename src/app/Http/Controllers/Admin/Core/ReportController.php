<?php

namespace App\Http\Controllers\Admin\Core;

use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Subscription;
use App\Models\PaymentLog;
use App\Models\CreditLog;
use App\Models\EmailCreditLog;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\PricingPlan;
use App\Models\GeneralSetting;
use App\Models\WhatsappCreditLog;
use App\Http\Utility\SendMail;
use App\Models\AndroidApi;
use Carbon\Carbon;
use App\Models\Gateway;
use App\Models\WhatsappDevice;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Gateway\WhatsappGatewayService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    public CustomerService $customerService;
    public function __construct(CustomerService $customerService) {

        $this->customerService = $customerService;
    }

    public function credit() {
        
        Session::put("menu_active", true);
        $title      = translate("Credit logs");
        $creditLogs = CreditLog::search(['user:name', 'user:email'])
                        ->with('user')
                        ->routefilter()
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());

        return view('admin.report.credit', compact('title', 'creditLogs'));
    }
    

    public function transaction() {

        Session::put("menu_active", true);
        $title          = translate("Transaction log");
        $paymentMethods = PaymentMethod::where('status', 1)->get();
        $transactions   = Transaction::with('user')
                            ->search(['transaction_number', 'amount', 'user:name', 'user:email'])
                            ->latest()
                            ->date()
                            ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                            ->appends(request()->all());
        return view('admin.report.record.transaction', compact('title', 'transactions', 'paymentMethods'));
    }

    public function subscription() {
        
        Session::put("menu_active", true);
        $title         = translate("Subscription history");
        $pricingPlan   = PricingPlan::where('status','=',1)->get();
        $subscriptions = Subscription::where('status', '!=', 0)
                            ->latest()
                            ->search(['trx_number', 'plan:name', 'amount', 'user:name', 'user:email'])
                            ->with('user', 'plan')
                            ->date()
                            ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                            ->appends(request()->all());
        return view('admin.report.record.subscription', compact('title', 'subscriptions', 'pricingPlan'));
    }

    public function paymentLog()
    {
        Session::put("menu_active", true);
        $title = translate("Payment Logs");
        $paymentLogs = PaymentLog::where('status', '!=', 0)
                                    ->with('user', 'paymentGateway')
                                    ->latest()
                                    ->search(['trx_number', 'amount', 'paymentGateway:name', 'user:name', 'user:email'])
                                    ->with('user', 'plan')
                                    ->date()
                                    ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                                    ->appends(request()->all());
        $paymentMethods = PaymentMethod::where('status', 1)->get();
        return view('admin.report.record.payment', compact('title', 'paymentLogs', 'paymentMethods'));
    }

    public function paymentDetail($id)
    {
        Session::put("menu_active", true);
        $title = translate("Payment Details");
        $paymentLog = PaymentLog::where('status', '!=', 0)->where('id', $id)->firstOrFail();
        return view('admin.report.payment_detail', compact('title', 'paymentLog'));
    }


    public function approve(Request $request)
    {
        $request->validate([
            'id'       => 'required|integer',
            'feedback' => 'required'
        ]);
        $paymentData = PaymentLog::where('id',$request->id)->where('status',1)->first();
        if($paymentData) {

            $paymentData->feedback = $request->has('feedback') ? $request->has('feedback') : "";
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
                    if($whatsappGatewayService->checkServerStatus()) {
                        
                        $whatsappGatewayService->sessionDelete($whatsapp_device->name);
                    }
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
            PaymentLog::where('user_id',$paymentData->user_id)->where('status', 1)->update(['status' => 3, 'feedback' => "Transaction Process Did Not Complete!"]);
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
                'time'            => Carbon::now(),
            ];
            
            $send = SendMail::MailNotification($user,'PAYMENT_CONFIRMED',$mailCode);
            $notify[] = ['success', "Payment has been approved. \t$send"];
            return back()->withNotify($notify);
        }
        return back();
    }

    public function reject(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $paymentLog = PaymentLog::where('id',$request->id)->where('status',1)->firstOrFail();
        $paymentLog->feedback = $request->input('feedback');
        $paymentLog->status = 3;
        $paymentLog->save();
        $paymentLog->plan->delete();
        $notify[] = ['success', 'Payment has been rejected.'];
        return back()->withNotify($notify);
    }
}