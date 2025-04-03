<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserStoreRequest;
use App\Services\System\User\AuthService;

class RegisterController extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * register
     *
     * @return View
     */
    public function register(): View {

        return view('user.auth.register');
    }

    /**
     * store
     *
     * @param UserStoreRequest $request
     * 
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request): RedirectResponse {

        return $this->authService->register($request);
    }
}
