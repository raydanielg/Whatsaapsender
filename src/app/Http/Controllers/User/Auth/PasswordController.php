<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\System\User\AuthService;
use App\Http\Requests\User\Auth\ForgotPasswordRequest;
use App\Http\Requests\User\Auth\UpdatePasswordRequest;
use App\Http\Requests\User\Auth\VerificationCodeRequest;
use App\Models\PasswordReset;

class PasswordController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * create
     *
     * @return View
     */
    public function create(): View {

        return view('user.auth.forgot-password');
    }

    /**
     * store
     *
     * @param ForgotPasswordRequest $request
     * 
     * @return RedirectResponse
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse {

        return $this->authService->storePasswordReset($request);
    }

    /**
     * passwordResetCodeVerify
     *
     * @return View|RedirectResponse
     */
    public function passwordResetCodeVerify(): View|RedirectResponse {

        if(!session()->get('password_reset_user_email')) 
            returnRedirectWithResponse(route: route('password.request'), status: "error", message: "Your email session expired please try again");
        
        return view('user.auth.verify_code');
    }

    /**
     * emailVerificationCode
     *
     * @param VerificationCodeRequest $request
     * 
     * @return RedirectResponse
     */
    public function emailVerificationCode(VerificationCodeRequest $request): RedirectResponse {

        return $this->authService->verifyCode($request);
    }

    /**
     * resendCode
     *
     * @return RedirectResponse
     */
    public function resendCode(): RedirectResponse{

        return $this->authService->resendCode();
    }

    /**
     * resetPassword
     *
     * @param string|null $token
     * 
     * @return View
     */
    public function resetPassword(?string $token = null): View|RedirectResponse { 

        $userResetToken = PasswordReset::where('token', $token)
                                            ->where('email', session()->get('password_reset_user_email'))                
                                            ->first();
        if(!$userResetToken) 
            return returnRedirectWithResponse(route: route('password.request'), message: "Invalid token");

        $token = $userResetToken->token;
        return view('user.auth.reset',compact('token'));
    }

    /**
     * updatePassword
     *
     * @param UpdatePasswordRequest $request
     * 
     * @return RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse {

        return $this->authService->updatePassword($request);
    }
}
