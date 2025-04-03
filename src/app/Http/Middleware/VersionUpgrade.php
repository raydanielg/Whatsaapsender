<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Session;

class VersionUpgrade
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $current_version = site_settings('app_version') ? site_settings('app_version') : "2.0.3"; //From next update this will come from general settings
        $latest_version = config('requirements.core.appVersion');

        $immune_routes = [
            'user.dashboard',
            'admin.update.verify',
            'admin.update.verify.store',
        ];
        if(Session::get('is_verified')) {
            
            $immune_routes = [
                'user.dashboard',
                'admin.update.index',
                'admin.update.version',
            ];
        }
        if(version_compare($latest_version, $current_version, '>') && !(request()->routeIs($immune_routes))) {
           
            if(empty(auth()->user())) {
                
                if(Session::get('is_verified')) {
                  
                    Session::forget('is_verified');
                    return redirect()->route('admin.update.index');
                    
                } else {

                    $notify[] = ['info', "Finish the update process"];
                    return redirect()->route('admin.update.verify')->withNotify($notify);
                }
                
            } else {
    
                $notify[] = ['info', "Admin needs to update the site to the latest version."];
                return redirect()->route('user.dashboard')->withNotify($notify);
            }
        } 
        elseif(version_compare($latest_version, $current_version, '==') && request()->routeIs('admin.update.*')) {
            return redirect()->route('admin.dashboard');
        }
        else {
            return $next($request);
        }
    }
}
