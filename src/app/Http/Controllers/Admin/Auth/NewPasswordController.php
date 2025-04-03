<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AdminPasswordReset;
use Carbon\Carbon;
use App\Http\Utility\SendMail;

class NewPasswordController extends Controller
{
    public function create()
    {
        $title = translate("forgot password");
        return view('admin.auth.forgot-password', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            $notify[] = ['error', 'Email not found.'];
            return back()->withNotify($notify);
        }
        AdminPasswordReset::where('email', $request->email)->delete();
        $passwordReset = AdminPasswordReset::create([
            'email' => $request->email,
            'token' => randomNumber(),
            'created_at' => Carbon::now(),
        ]);
        $mailCode = [
            'code' => $passwordReset->token, 
            'time' => $passwordReset->created_at,
        ];
        SendMail::MailNotification($admin,'ADMIN_PASSWORD_RESET',$mailCode);
        session()->put('admin_password_reset_user_email', $request->email);
        $notify[] = ['success', 'Check your email password reset code sent successfully'];
        return redirect(route('admin.password.verify.code'))->withNotify($notify);
    }

    public function passwordResetCodeVerify(){
        $title = translate("Admin Password Reset");
        if(!session()->get('admin_password_reset_user_email')) {
            $notify[] = ['error','Your email session expired please try again'];
            return redirect()->route('admin.password.request')->withNotify($notify);
        }
        return view('admin.auth.verify',compact('title'));
    }

    public function emailVerificationCode(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);
        $code = preg_replace('/[ ,]+/', '', trim($request->code));
        $email = session()->get('admin_password_reset_user_email');
        $adminResetToken = AdminPasswordReset::where('email', $email)->where('token', $code)->first();
        if(!$adminResetToken){
        	if(session()->get('admin_password_reset_user_email')){
	            session()->forget('admin_password_reset_user_email');
	        }
            $notify[] = ['error', 'Invalid token'];
            return redirect(route('admin.password.request'))->withNotify($notify);
        }
        $notify[] = ['success', 'Change your password.'];
        return redirect()->route('admin.password.reset', $code)->withNotify($notify);

    }

}
