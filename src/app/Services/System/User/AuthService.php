<?php

namespace App\Services\System\User;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use Illuminate\View\View;
use App\Enums\Common\Status;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Jobs\RegisterMailJob;
use App\Http\Utility\SendMail;
use App\Services\Core\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class AuthService
{
     public UserService $userService;
 
     public function __construct() {

         $this->userService = new UserService;
     }

     /**
      * register
      *
      * @param Request $request
      * 
      * @return RedirectResponse
      */
     public function register(Request $request): RedirectResponse {

          $user = User::create([
               'name'                      => $request->input('name'),
               'email'                     => $request->input('email'),
               'gateway_credentials'       => config('setting.gateway_credentials'),
               'password'                  => Hash::make($request->input('password')),
               'email_verified_code'       => randomNumber(),
               'email_verified_send_at'    => carbon(),
          ]);

          $this->userService->applyOnboardingBonus($user);
          $this->userService->handleEmailVerification($user);
          Auth::login($user);
          
          return redirect()->route('user.dashboard');
     }

     /**
      * storePasswordReset
      *
      * @param Request $request
      * 
      * @return RedirectResponse
      */
     public function storePasswordReset(Request $request): RedirectResponse {

          $email = $request->input("email");
          $user = User::active()
                         ->where("email", $email)
                         ->first();
                         
          if(!$user) return returnBackWithResponse(message: "Ineligible User to perform this action");

          PasswordReset::where('email', $request->input('email'))
                              ->delete();

          return $this->createAndSendResetRequest($user, $email);
     }

     /**
      * verifyCode
      *
      * @param Request $request
      * 
      * @return RedirectResponse
      */
     public function verifyCode(Request $request): RedirectResponse {

          $code     = preg_replace('/[ ,]+/', '', trim($request->code));
          $token    = PasswordReset::where('token', $code)
                                        ->first();
          if(!$token) return returnBackWithResponse(message: 'Invalid token');

          return returnRedirectWithResponse(route: route('password.reset', $code), status: 'success', message: "Change your password.");
     }

     /**
      * resendCode
      *
      * @return RedirectResponse
      */
     public function resendCode(): RedirectResponse {

          $email = session()->get('password_reset_user_email');
          $user  = User::active()->where('email',$email)->first();
          if(!$user) return returnBackWithResponse(message: "Ineligible User to perform this action");
          $reset = PasswordReset::where('email', $email)->first();
          if(!$reset) return returnBackWithResponse(message: "Your email session expired please try again");

          if (!Carbon::parse($reset->created_at)->addMinute()->isPast()) 
               return returnBackWithResponse(message:"Verification message code not received. Please check your inbox and spam folder. If you have not received the code after 1 minute, please request a new code");
           
          $reset->delete();
          return $this->createAndSendResetRequest($user, $email);
     }

     /**
      * updatePassword
      *
      * @param Request $request
      * 
      * @return RedirectResponse
      */
     public function updatePassword(Request $request): RedirectResponse {

          $email    = session()->get('password_reset_user_email');
          $user     = User::active()
                              ->where('email', $email)
                              ->first();

          if(!$user) return returnBackWithResponse(message: "Ineligible User to perform this action");
          
          $token = PasswordReset::where('token', $request->input('token'))
                                             ->first();
          if(!$token) return returnRedirectWithResponse(route: route('password.request'),message: "Invalid token");
          $user->password = Hash::make($request->password);
          $user->save();

          if(session()->get('password_reset_user_email')) session()->forget('password_reset_user_email');
           
          $mailCode = [
               'time' => carbon(),
               'name' => site_settings(SettingKey::SITE_NAME->value, "")
          ];
   
          RegisterMailJob::dispatch($user, 'PASSWORD_RESET_CONFIRM', $mailCode);
          
          $token->delete();
          return returnRedirectWithResponse(route: route('login'), status:"success", message: "Password changed successfully");
     }

     /**
      * logout
      *
      * @return RedirectResponse
      */
     public function logout(): RedirectResponse {

          $lang = session('lang');
          $flag = session('flag');

          Auth::guard('web')->logout();

          // request()->session()->invalidate();
          request()->session()->regenerateToken();

          session()->put('lang', $lang);
          session()->put('flag', $flag);

          return returnRedirectWithResponse(route:url("/"), status:"success", message:"Successfully Logged out");
     }

     /**
      * processEmailAuthorization
      *
      * @param User|null $user
      * 
      * @return RedirectResponse
      */
     public function processEmailAuthorization(?User $user = null): RedirectResponse|View {
          
          if(!$user) return returnRedirectWithResponse(route:url("/"), message:"Could not retrieve User, please try again later");
          try {
               
               $verifyUserEmailVerified = $user->email_verified_status == StatusEnum::TRUE->status() 
                                        || $user->email_verified_status == Status::ACTIVE->value;
                                        
               if($verifyUserEmailVerified) return redirect()->route('user.dashboard');

               $registrationOtpStatus = site_settings(SettingKey::REGISTRATION_OTP_VERIFICATION->value, Status::INACTIVE->value);
               $registrationOtpStatus = $registrationOtpStatus == StatusEnum::TRUE->status() ||
                                             $registrationOtpStatus == Status::ACTIVE->value;
                                             
               $emailOtpStatus = site_settings(SettingKey::EMAIL_OTP_VERIFICATION->value, Status::INACTIVE->value);
               $emailOtpStatus = $emailOtpStatus == StatusEnum::TRUE->status() ||
                                   $emailOtpStatus == Status::ACTIVE->value;
                                   
               if($registrationOtpStatus && $emailOtpStatus) return view('user.auth.email');
               
               return redirect()->route('user.dashboard');
          } catch(Exception $e) {
               
               $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
               return back()->withNotify($notify);
          }
     }

     /**
      * sendNotification
      *
      * @param User $user
      * 
      * @return RedirectResponse
      */
     public function sendNotification(User $user): RedirectResponse {

          if ($this->checkAuthorizationValidationCode($user, $user->email_verified_code)) 
               return returnBackWithResponse(message: 'Verification message code not received. Please check your inbox and spam folder. If you have not received the code after 1 minute, please request a new code');

          $user->email_verified_code    = randomNumber();
          $user->email_verified_send_at = carbon();
          $user->save();

          $mailCode = [
               'name' => site_settings("site_name"),
               'code' => $user->email_verified_code,
               'time' => carbon(),
          ];
   
          SendMail::MailNotification($user, 'REGISTRATION_VERIFY', $mailCode);
          return returnBackWithResponse(message: "Email Verification code Send");
     }

     /**
      * processEmailVerification
      *
      * @param User $user
      * @param Request $request
      * 
      * @return RedirectResponse
      */
     public function processEmailVerification(User $user, Request $request): RedirectResponse {

          if ($user->email_verified_code !== $request->input('code')) 
               return returnBackWithResponse(message: "Verification code did not match");

          //Todo: Update status column enums
          $user->email_verified_status = StatusEnum::TRUE->status();
          $user->email_verified_code = null;
          $user->email_verified_at = carbon();
          $user->save();
     
          return redirect()->route('user.dashboard');
     }

     /**
      * checkAuthorizationValidationCode
      *
      * @param User $user
      * @param mixed $code
      * 
      * @return bool
      */
     private function checkAuthorizationValidationCode(User $user, $code): bool
     {
          if (Carbon::parse($user->email_verified_send_at)->addMinute()->isPast() 
               || $user->email_verified_code !== $code) return false;
          
          return true;
     }

     /**
      * createAndSendResetRequest
      *
      * @param User $user
      * @param string $email
      * 
      * @return RedirectResponse
      */
     private function createAndSendResetRequest(User $user, string $email): RedirectResponse {

          $passwordReset = PasswordReset::create([
               'email'      => $email,
               'token'      => randomNumber(),
               'created_at' => carbon(),
          ]);

          $mailCode = [
               'code' => @$passwordReset?->token ? $passwordReset->token : translate("N/A"),
               'time' => @$passwordReset?->created_at ? $passwordReset->created_at : translate("N/A"),
          ];

          SendMail::MailNotification($user,'PASSWORD_RESET',$mailCode);
          session()->put('password_reset_user_email', $email);

          return returnRedirectWithResponse(route: route('password.verify.code'), status: "success", message: "Check your email password reset code sent successfully");
     }
}