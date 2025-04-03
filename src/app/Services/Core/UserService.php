<?php

namespace App\Services\Core;

use App\Models\User;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use App\Models\PricingPlan;
use App\Models\Subscription;
use App\Enums\Common\Status;
use App\Jobs\RegisterMailJob;
use App\Enums\SubscriptionStatus;
use Illuminate\Support\Facades\DB;

class UserService
{
     /**
      * applyOnboardingBonus
      *
      * @param User $user
      * 
      * @return bool
      */
     public function applyOnboardingBonus(User $user): bool {
          
          $onboardingBonus = site_settings(SettingKey::ONBOARDING_BONUS->value);
          $isOnboardingBonusInactive = $onboardingBonus == StatusEnum::FALSE->status() || 
                                             $onboardingBonus == Status::INACTIVE->value;
          if($isOnboardingBonusInactive) return false;

          $onboardingBonusPlanId = site_settings(SettingKey::ONBOARDING_BONUS_PLAN->value);
          if($onboardingBonusPlanId) return false;

          $plan = PricingPlan::active()
                                   ->where("id", $onboardingBonusPlanId)
                                   ->first();
          if(!$plan) return false;
          
          return DB::transaction(function () use ($plan, $user): bool {

               $user->sms_credit      = @$plan?->sms?->is_allowed && @$plan->sms?->credits 
                                             ? $plan->sms->credits 
                                             : 0;
               $user->email_credit    = @$plan?->email?->is_allowed && @$plan->email?->credits 
                                             ? $plan->email->credits 
                                             : 0;
               $user->whatsapp_credit = @$plan?->whatsapp?->is_allowed && @$plan->whatsapp?->credits 
                                             ? $plan->whatsapp->credits 
                                             : 0;
               $user->save();

               Subscription::create([

                    'user_id'      => $user->id,
                    'plan_id'      => $plan->id,
                    'expired_date' => carbon()->addDays($plan->duration),
                    'amount'       => $plan->amount,
                    'trx_number'   => trxNumber(),
                    'status'       => SubscriptionStatus::RUNNING->value,
               ]);

               return true;
          });
     }

     /**
      * handleEmailVerification
      *
      * @param User $user
      * 
      * @return void
      */
     public function handleEmailVerification(User $user): void {
        
          $verifyRegisterOTP = site_settings(SettingKey::REGISTRATION_OTP_VERIFICATION->value);
          $verifyRegisterOTP = $verifyRegisterOTP == StatusEnum::TRUE->status() ||
                                   $verifyRegisterOTP == Status::ACTIVE->value;

          $verifyEmailOTP = site_settings(SettingKey::EMAIL_OTP_VERIFICATION->value);
          $verifyEmailOTP = $verifyEmailOTP == StatusEnum::TRUE->status() ||
                                   $verifyEmailOTP == Status::ACTIVE->value;

          
          if(!$verifyRegisterOTP && !$verifyEmailOTP) {
  
              $user->email_verified_status = StatusEnum::FALSE->status();
              $user->email_verified_code   = null;
              $user->email_verified_at     = carbon();
          } else {
             
              $mailCode = [
                  'name' => site_settings("site_name"),
                  'code' => $user->email_verified_code,
                  'time' => carbon()->parse()->toDayDateTimeString(),
              ];
              RegisterMailJob::dispatch($user, 'REGISTRATION_VERIFY', $mailCode);
          }
          $user->save();
     }
}