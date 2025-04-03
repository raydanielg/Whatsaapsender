<?php

namespace App\Http\Controllers\User\Auth;

use App\Enums\SettingKey;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\System\User\AuthService;

class LoginController extends Controller
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

        return view('user.auth.login');
    }

    /**
     * store
     *
     * @param LoginRequest $request
     * 
     * @return RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse {

        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(SettingKey::ROUTE_USER_DASHBOARD->value);
    }
}
