<?php

namespace App\Http\Controllers\User;

use App\Enums\StatusEnum;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use Carbon\Carbon;
use App\Http\Utility\PaymentInsert;
use App\Models\PaymentLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PlanController extends Controller
{

    /**
     * @return View
     */
    public function create(): View
    {
        Session::put("menu_active", false);
    	$title = translate("Available Membership Plans");
    	$plans = PricingPlan::where('status', StatusEnum::TRUE->status())
            ->where('amount', '>=','1')
            ->orderBy('amount', 'ASC')
            ->get();
       
    	$paymentMethods = PaymentMethod::where('status', 1)->get();
        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', SubscriptionStatus::RUNNING->value)
            ->orWhere('status', SubscriptionStatus::RENEWED->value)
            ->latest()->first();
       
    	return view('user.plan.create',compact('title', 'plans', 'paymentMethods', 'subscription'));
    }

    public function makePayment($id) {

        Session::put("menu_active", false);
        $status = 'error';
        $message = 'Something went wrong';
        try {
            $title = translate("Make Payment");
            $plan = PricingPlan::find($id);
            if($plan) {
                $payment_methods = PaymentMethod::where('status', StatusEnum::TRUE->status())->get();
                return view('user.plan.make_payment', compact('title', 'plan', 'payment_methods', 'id'));
            } else {

                $message = translate("Plan couldn't be fetched, please contact Admin");
            }

            
        } catch (\Exception $e) {

            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
        
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
    	$request->validate([
    		'id' => 'required|exists:pricing_plans,id',
    		'payment_gateway' => 'required|exists:payment_methods,id',
    	]);

    	$user = Auth::user();
        PaymentLog::where('user_id',$user->id)->where('status', 0)->delete();
        Subscription::where('user_id',$user->id)->where('status', SubscriptionStatus::REQUESTED->value)->delete();

    	$plan          = PricingPlan::where('id', $request->input('id'))->where('status', StatusEnum::TRUE->status())->firstOrFail();
        $subscription  = Subscription::where('user_id', $user->id)->where('status', '!=', 0)->first();
        $paymentMethod = PaymentMethod::where('id', $request->input('payment_gateway'))->where('status', StatusEnum::TRUE->status())->first();
        $subscription  = Subscription::create([
            'user_id'      => $user->id,
            'plan_id'      => $plan->id,
            'expired_date' => Carbon::now()->addDays($plan->duration),
            'amount'       => $plan->amount,
            'trx_number'   => trxNumber(),
            'status'       => $request->input("status"),
        ]);
    	session()->put('subscription_id', $subscription->id);
        $payment_log = PaymentInsert::paymentCreate($paymentMethod->unique_code);
        return response()->json([

            'status'  => 'success',
            'message' => translate("Successfully initiated the payment process"),
            'data' => [
                'payment_log' => $payment_log, 
                'plan' => $plan, 
                'payment_method' => $paymentMethod]
        ]);
    }

    /**
     * @return View
     */
    public function subscription(): View
    {
        Session::put("menu_active", true);
        $title = translate("Subscription List");
        $user = Auth::user();
        $paymentMethods = PaymentMethod::where('status', 1)->get();
        $subscriptions = Subscription::where('status', '!=', 0)
                                        ->where("user_id", $user->id)
                                        ->latest()
                                        ->search(['trx_number', 'amount', 'plan:name'])
                                        ->with('user', 'plan')
                                        ->date()
                                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                                        ->appends(request()->all());
        return view('user.subscription', compact('title', 'subscriptions', 'paymentMethods'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function subscriptionRenew(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $subscriptionPlan = Subscription::where('id', $request->input('id'))->where('user_id', $user->id)->firstOrFail();
        session()->put('subscription_id', $subscriptionPlan->id);
        $paymentMethod = PaymentMethod::where('id', $request->input('payment_gateway'))->where('status', 1)->first();
        PaymentInsert::paymentCreate($paymentMethod->unique_code);

        return redirect()->route('user.payment.preview');
    }
}
