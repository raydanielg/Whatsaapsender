<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\System\User\AuthService;

class AuthenticatedSessionController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService) {
        
        $this->authService = $authService;
    }

    /**
     * logout
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse {

        return $this->authService->logout();
    }
}
