<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AdminPasswordReset;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Utility\SendMail;

class ResetPasswordController extends Controller
{
    public function create(Request $request, $token)
    {
        $title = translate("Password change");
        $passwordToken = $token;
        $email = session()->get('admin_password_reset_user_email');
        $userResetToken = AdminPasswordReset::where('email', $email)->where('token', $token)->first();
        if(!$userResetToken){
        	if(session()->get('admin_password_reset_user_email')){
	            session()->forget('admin_password_reset_user_email');
	        }
            $notify[] = ['error', 'Invalid token'];
            return redirect(route('admin.password.request'))->withNotify($notify);
        }
        return view('admin.auth.reset',compact('title', 'passwordToken'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:4',
            'token' => 'required|exists:admin_password_resets,token',
        ]);
        $email = session()->get('admin_password_reset_user_email');
        $userResetToken = AdminPasswordReset::where('email', $email)->where('token', $request->token)->first();
        if(!$userResetToken){
            $notify[] = ['error', 'Invalid token'];
            return redirect(route('admin.password.request'))->withNotify($notify);
        }
        $userResetToken->delete();
        $admin = Admin::where('email', $email)->first();
        $admin->password = Hash::make($request->password);
        $admin->save();

        if(session()->get('admin_password_reset_user_email')){
            session()->forget('admin_password_reset_user_email');
        }
        $mailCode = [
            'time' => Carbon::now(),
            'name' => site_settings("site_name")
        ];
        SendMail::MailNotification($admin,'PASSWORD_RESET_CONFIRM',$mailCode);
        $userResetToken->delete();
        $notify[] = ['success', 'Password changed successfully'];
        return redirect(route('admin.login'))->withNotify($notify);
    }
}
