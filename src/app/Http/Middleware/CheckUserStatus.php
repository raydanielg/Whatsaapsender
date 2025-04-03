<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\System\User\AuthService;

class CheckUserStatus
{
    public AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next)
    {
        if(Auth::check() && Auth::user()->status == StatusEnum::FALSE->status()) {
            $notify[] = ['error',translate("Your account is banned by admin")];
            return $this->authService->logout()->withNotify($notify);
        }
        return $next($request);
    }
}
