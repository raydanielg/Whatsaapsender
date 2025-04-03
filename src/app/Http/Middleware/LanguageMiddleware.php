<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use App\Enums\StatusEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\RedirectResponse )  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            if(!session()->has('locale')) {

                $locale = (Language::where('is_default',(StatusEnum::TRUE)->status())->first())->code;
                session()->put('locale', $locale);
            } else {

                $locale = session()->get('locale');
            }
            App::setLocale($locale);

        } catch (\Exception $ex) {
        
        }

        return $next($request);
    }
}
