<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DemoMode
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeArray = [
            'admin/manual/payment/update/{id}',
            'admin/bee-plugin/update',
            'admin/payment/methods/update/{id}',
            'admin/general/setting/social-login/update',
            'whatsapp/gateway/delete',
            'admin/language/default',
            'admin/profile/update',
            'admin/password/update',
            'admin/payment/update/{id}',
            'admin/manual/payment/update/{id}',
            'admin/general/setting/store',
            'admin/social/login/update',
            'admin/general/setting/currency/delete',
            'admin/general/setting/currency/update',
            'admin/frontend/section/store',
            'admin/language/delete',
            'admin/language/update',
            'admin/mail/update/{id}',
            'admin/plans/delete',
            'admin/plans/update',
            'admin/user/update/{id}',
            'user/profile/update',
            'user/password/update',
            'admin/frontend/section/save/content/{section_key}',
            'admin/frontend/section/element/content/{section_key}/{id?}',
        ];
        if (env('APP_MODE')=='demo') {
            if($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')){
                if (in_array($request->route()->uri, $routeArray)) {
                    $notify[] = ['error', 'In the demo version, You can not do anything with this option'];
                    return back()->withNotify($notify);
                }
            }
        }
        return $next($request);
    }
}
