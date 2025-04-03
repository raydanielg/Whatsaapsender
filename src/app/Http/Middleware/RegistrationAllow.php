<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Enums\Common\Status;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class RegistrationAllow
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): RedirectResponse|Response
    {   
        $memberAuthentication = site_settings(SettingKey::MEMBER_AUTHENTICATION->value);

        if(!$memberAuthentication) return $next($request);
        $memberAuthentication = json_decode($memberAuthentication, true);
        
        $canRegister = Arr::get($memberAuthentication, SettingKey::REGISTRATION->value);
        if(!$canRegister) return $next($request);

        if($canRegister == StatusEnum::FALSE->status() ||
            $canRegister == Status::INACTIVE->value) {

            $notify[] = ['error', translate('Registration is currently off')];
            return back()->withNotify($notify);
        }
        return $next($request);
    }
}
