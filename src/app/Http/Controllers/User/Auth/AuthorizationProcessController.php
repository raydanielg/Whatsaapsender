<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\System\User\AuthService;
use App\Http\Requests\User\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthorizationProcessController extends Controller
{
    public AuthService $authService;

    public function __construct() {

        $this->authService = new AuthService;
        
    }

    /**
     * process
     *
     * @return RedirectResponse
     */
    public function process(): RedirectResponse|View
    {
        $user = getAuthUser('web');
        return $this->authService->processEmailAuthorization($user);
    }

    /**
     * sendNotification
     *
     * @return RedirectResponse
     */
    public function sendNotification(): RedirectResponse
    {
        $user = getAuthUser('web');
        return $this->authService->sendNotification($user);
    }

    /**
     * processEmailVerification
     *
     * @param EmailVerificationRequest $request
     * 
     * @return RedirectResponse
     */
    public function processEmailVerification(EmailVerificationRequest $request):RedirectResponse
    {
        $user = getAuthUser('web');
        return $this->authService->processEmailVerification($user, $request);
    }
}