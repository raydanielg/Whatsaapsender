<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class SystemController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function cacheClear() {

        Artisan::call('optimize:clear');
        $notify[] = ['success', translate('Cache cleared successfully')];
        return back()->withNotify($notify);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function systemInfo() {

        Session::put("menu_active", true);
        $title = translate("System Information");

        $systemInfo = [
            'laravelversion' => app()->version(),
            'serverdetail'   => $_SERVER,
            'phpversion'     => phpversion(),
        ];
        return view('admin.system_info',compact('title','systemInfo'));
    }

}
