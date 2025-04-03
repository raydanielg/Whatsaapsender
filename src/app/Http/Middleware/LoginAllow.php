<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use Illuminate\Support\Arr;
use App\Enums\Common\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginAllow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): RedirectResponse|Response
    {
        $memberAuthentication = site_settings(SettingKey::MEMBER_AUTHENTICATION->value);

        if(!$memberAuthentication) return $next($request);
        $memberAuthentication = json_decode($memberAuthentication, true);
        
        $canLogin = Arr::get($memberAuthentication, SettingKey::LOGIN->value);
        if(!$canLogin) return $next($request);
        
        if($canLogin == StatusEnum::FALSE->status() ||
            $canLogin == Status::INACTIVE->value) {

                $notify[] = ['error', translate('Login is currently off')];
            return back()->withNotify($notify);
        }
        return $next($request);
    }
}
