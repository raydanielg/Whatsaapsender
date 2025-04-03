<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Enums\Common\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): RedirectResponse|Response|JsonResponse
    {
        $maintenanceMode = site_settings(SettingKey::MAINTENANCE_MODE->value);
        if(!$maintenanceMode) return $next($request);

        $isActive = $maintenanceMode == StatusEnum::TRUE->status() || $maintenanceMode == Status::ACTIVE->value;

        if($isActive) {
            $siteName   = site_settings(SettingKey::SITE_NAME->value);
            $message    = site_settings(SettingKey::MAINTENANCE_MODE_MESSAGE->value);
            return new Response(view('errors.maintenance',compact('siteName','message')));
        }
        return $next($request);
    }
}
