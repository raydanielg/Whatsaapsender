<?php

namespace App\Service\Admin\Core;

use App\Enums\CommunicationStatusEnum;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SMSlog;
use App\Models\EmailLog;
use App\Models\CreditLog;
use App\Enums\StatusEnum;
use App\Enums\ServiceType;
use App\Models\PricingPlan;
use App\Models\WhatsappLog;
use App\Models\Subscription;
use App\Jobs\RegisterMailJob;
use App\Enums\SubscriptionStatus;
use App\Http\Requests\UserCreditRequest;
use App\Models\CommunicationLog;
use App\Service\Admin\Dispatch\SmsService;
use Illuminate\Pagination\AbstractPaginator;

class CustomerService
{
    /**
     * @param $userId
     * 
     * @return User
     * 
     */
    public function findById(int $userId): User {

        return User::where('id', $userId)->first();
    }
    /**
     * @param $userUid
     * 
     * @return User
     * 
     */
    public function findByUid(string $userUid): User {

        return User::where('uid', $userUid)->first();
    }

    /**
     * @return AbstractPaginator
     * 
     */
    public function getPaginateUsers(): AbstractPaginator {

        return User::search(['name','email'])
                ->routefilter()
                ->filter(['email_verified_status'])
                ->latest()
                ->date()
                ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                ->appends(request()->all());
    }

    /**
     * @param User $user
     * 
     * @return array $notify
     * 
     */
    public function applyOnboardingBonus(User $user): array {

        $plan = PricingPlan::find(site_settings('onboarding_bonus_plan'));
        
        if(site_settings('onboarding_bonus') == StatusEnum::FALSE->status() || !$plan) {
            
            $notify[] = ['success', translate("Added new user succesfully")];
            return $notify;
        }
        
        $user->sms_credit      = $plan->sms->is_allowed ? $plan->sms->credits : 0;
        $user->email_credit    = $plan->email->is_allowed ? $plan->email->credits : 0;
        $user->whatsapp_credit = $plan->whatsapp->is_allowed ? $plan->whatsapp->credits : 0;
        
        $user->save();

        Subscription::create([

            'user_id'      => $user->id,
            'plan_id'      => $plan->id,
            'expired_date' => Carbon::now()->addDays($plan->duration),
            'amount'       => $plan->amount,
            'trx_number'   => trxNumber(),
            'status'       => SubscriptionStatus::RUNNING->value,
        ]);
        $notify[] = ['success', translate("Added new user with "). $plan->name. translate(" as an onboarding bonus.")];
        return $notify;
    }
    /**
     * @param int $userId
     * 
     * @return array
     * 
     */
    public function logs(int $userId): array {

         return [

            "sms" => [
                'all'     => CommunicationLog::where('type', ServiceType::SMS->value)->count(),
                'success' => CommunicationLog::where('type', ServiceType::SMS->value)->where('status', CommunicationStatusEnum::DELIVERED->value)->count(),
                'pending' => CommunicationLog::where('type', ServiceType::SMS->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                'failed'  => CommunicationLog::where('type', ServiceType::SMS->value)->where('status', CommunicationStatusEnum::FAIL->value)->count(),
            ],
            "email" => [
                'all'     => CommunicationLog::where('type', ServiceType::EMAIL->value)->count(),
                'success' => CommunicationLog::where('type', ServiceType::EMAIL->value)->where('status', CommunicationStatusEnum::DELIVERED->value)->count(),
                'pending' => CommunicationLog::where('type', ServiceType::EMAIL->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                'failed'  => CommunicationLog::where('type', ServiceType::EMAIL->value)->where('status', CommunicationStatusEnum::FAIL->value)->count(),
            ],
            "whats_app" => [
                'all'     => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->count(),
                'success' => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->where('status', CommunicationStatusEnum::DELIVERED->value)->count(),
                'pending' => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                'failed'  => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->where('status', CommunicationStatusEnum::FAIL->value)->count(),
            ]
        ];
    }

    /**
     * @param UserCreditRequest $request
     * 
     * @return array
     * 
     */
    public function buildCreditArray(UserCreditRequest $request): array {

        $data = [];
        foreach(array_keys(ServiceType::toArray()) as $key) {
    
            $data[strtolower($key)] = (int)$request->input(strtolower($key).'_credit', 0);
        }
        return $data;
    }

    /**
     * @param User $user
     * 
     * @param int $totalCredit
     * 
     * @param int $serviceType
     * 
     * @param string $message
     * 
     * @return void
     * 
     */
    public function deductCreditLog($user, int|null $totalCredit, int $serviceType, bool $manual = false, string $message = null): void {

        
        $column_name = strtolower(ServiceType::getValue($serviceType))."_credit";
        
        $creditInfo              = new CreditLog();
        $creditInfo->user_id     = $user->id;
        $creditInfo->type        = $serviceType;
        $creditInfo->manual      = $manual ? StatusEnum::TRUE->status() : StatusEnum::FALSE->status();
        $creditInfo->credit_type = StatusEnum::FALSE->status();
        $creditInfo->credit      = $totalCredit ?? 0;
        $creditInfo->trx_number  = trxNumber();
        $creditInfo->post_credit = $user->$column_name;
        $creditInfo->details     = $message ? $message : $totalCredit.translate(" credit deducted for sending ").ucfirst(strtolower(ServiceType::getValue($serviceType))).translate(" content");
        $creditInfo->save();
        
        if($user->$column_name != -1) {
            
            $user->$column_name -= $totalCredit;
            $user->$column_name = $user->$column_name <= -1 ? -1 : $user->$column_name;
        }
        $user->save();
    }

    /**
     * @param User $user
     * 
     * @param int $totalCredit
     * 
     * @param int $serviceType
     * 
     * @param string $message
     * 
     * @return void
     * 
     */
    public static function addedCreditLog($user, int|null $totalCredit, int $serviceType, bool $manual = false, string $message = null): void {

        $column_name = strtolower(ServiceType::getValue($serviceType))."_credit";
        
        if($user->$column_name > -1) {
            
            $creditInfo              = new CreditLog();
            $creditInfo->user_id     = $user->id;
            $creditInfo->type        = $serviceType;
            $creditInfo->manual      = $manual ? StatusEnum::TRUE->status() : StatusEnum::FALSE->status();
            $creditInfo->credit_type = StatusEnum::TRUE->status();
            $creditInfo->credit      = $totalCredit ?? 0;
            $creditInfo->trx_number  = trxNumber();
            $creditInfo->post_credit = $user->$column_name;
            $creditInfo->details     = $message ? $message : $totalCredit.' '.ucfirst(strtolower(ServiceType::getValue($serviceType))).translate(" credit added");
            $creditInfo->save();
            
            $user->$column_name += $totalCredit;
            $user->save();
        } 
    }

    /**
     * @param User $user
     * 
     * @param $request
     * 
     * @return void
     * 
     */
    public static function updatePlan(User $user, $request) {

        $new_plan = PricingPlan::where("id", $request->input("pricing_plan"))->firstorFail();

        Subscription::where([

            "user_id" => $user->id, 
            "status" => Subscription::RUNNING
        ])->update([

            "status" => Subscription::INACTIVE
        ]);

        Subscription::create([

            "user_id"      => $user->id,
            "plan_id"      => $request->input("pricing_plan"),
            "amount"       => $new_plan->amount,
            "expired_date" => Carbon::now()->addDays($new_plan->duration),
            "trx_number"   => trxNumber(),
            "status"       => Subscription::RUNNING,
        ]);
        $user->sms_credit      = $new_plan->sms->credits;
        $user->email_credit    = $new_plan->email->credits;
        $user->whatsapp_credit = $new_plan->whatsapp->credits;
    } 

    public function canSpendCredits($user, $allowed_access, $type, $quantity = null) {

        
        $pass = false;
        $allowed_per_day = array_key_exists('credits_per_day', $allowed_access->{strtolower(ServiceType::getValue($type))}) 
                            ? $allowed_access->{strtolower(ServiceType::getValue($type))}['credits_per_day'] 
                            : 0;
        
       

        if ($allowed_per_day == 0) {
            $pass = true;
        } else {

            if($quantity && $allowed_per_day < $quantity) {

                return false;
            }
            
            $baseQuery = CreditLog::where('user_id', $user->id)
                ->where('type', $type)
                ->where('manual', StatusEnum::FALSE->status())
                ->whereDate('created_at', Carbon::today());
    
            $credits_deducted = (clone $baseQuery)
                ->where('credit_type', StatusEnum::FALSE->status())
                ->sum('credit');
    
            $credits_added = (clone $baseQuery)
                ->where('credit_type', StatusEnum::TRUE->status())
                ->sum('credit');
    
            $net_credits_spent = $credits_deducted - $credits_added;
            if ($net_credits_spent < $allowed_per_day) {
                $pass = true;
            }
        }
        return $pass;
    }
}