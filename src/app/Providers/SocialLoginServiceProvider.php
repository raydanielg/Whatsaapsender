<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Config;
class SocialLoginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $google_creds = json_decode(site_settings("social_login_with"), true)["google_oauth"];
            $google = [
                'client_id' => $google_creds["client_id"],
                'client_secret' => $google_creds["client_secret"],
                'redirect' => url('auth/google/callback'),
            ];
            Config::set('services.google', $google);

        } catch(\Exception $exception) {

            \Log::error("Social Login failed: " . $exception->getMessage());
        }   
    }
}
