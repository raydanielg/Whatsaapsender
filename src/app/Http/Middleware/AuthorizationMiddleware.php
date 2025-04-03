<?php

namespace App\Http\Middleware;

use App\Enums\Common\Status;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authenticatedUser = getAuthUser("web");
        
        if(!$authenticatedUser) return returnRedirectWithResponse(route:url("/"), message:"Ineligible User to perform this action");

        $isActive = $authenticatedUser->status == StatusEnum::TRUE->status() || 
                        $authenticatedUser->status == Status::ACTIVE->value;
        if(!$isActive) return returnRedirectWithResponse(route:url("/"), message:"Ineligible User to perform this action");

        $isEmailVerified = $authenticatedUser->email_verified_status == StatusEnum::TRUE->status() || 
                                $authenticatedUser->email_verified_status == Status::ACTIVE->value;

        $registrationOtpStatus = site_settings(SettingKey::REGISTRATION_OTP_VERIFICATION->value, Status::INACTIVE->value);
        $registrationOtpStatus = $registrationOtpStatus == StatusEnum::TRUE->status() ||
                                        $registrationOtpStatus == Status::ACTIVE->value;

        $emailOtpStatus = site_settings(SettingKey::EMAIL_OTP_VERIFICATION->value, Status::INACTIVE->value);
        $emailOtpStatus = $emailOtpStatus == StatusEnum::TRUE->status() ||
                            $emailOtpStatus == Status::ACTIVE->value;
        
        if(!$isEmailVerified && $registrationOtpStatus && $emailOtpStatus) return returnRedirectWithResponse(route:route("user.authorization.process"), message:"Please verify your Email Address");

        return $next($request);
    }
}
