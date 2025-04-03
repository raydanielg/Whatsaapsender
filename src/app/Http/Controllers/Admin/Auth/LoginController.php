<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct() {

        $this->middleware('admin.guest')->except('logout');
    }

    /**
     * showLogin
     *
     * @return View
     */
    public function showLogin(): View {

        $title = translate("Admin Login");
        return view('admin.auth.login', compact('title'));
    }

    /**
     * authenticate
     *
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function authenticate(Request $request): RedirectResponse {

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::guard('admin')->attempt($credentials)) {

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * logout
     *
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse { 

        $lang = Session::get('lang');
        $flag = Session::get('flag');
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); 
        return $this->loggedOut(request: $request, lang: $lang, flag: $flag) ?: redirect('/admin');
    }

    /**
     * loggedOut
     *
     * @param Request $request
     * @param mixed $lang
     * @param mixed $flag
     * 
     * @return void
     */
    protected function loggedOut(Request $request, $lang, $flag): void {
        
        Session::put('lang',$lang);
        Session::put('flag',$flag);
    }
}
