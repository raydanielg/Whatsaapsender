<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PaymentMethod;
use Config;

class PaymentServiceProvider extends ServiceProvider
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
            $paypal = PaymentMethod::where('unique_code', 'PAYPAL102')->first();
            if($paypal){
                $config = array(
                    'client_id' => $paypal->payment_parameter->client_id,
                    'secret' => $paypal->payment_parameter->secret,
                    'settings' => array(
                        'mode' => @$paypal->payment_parameter->environment ?? 'sandbox',
                        'http.ConnectionTimeOut' => 1000,
                        'log.LogEnabled' => true,
                        'log.FileName' => storage_path() . '/logs/paypal.log',
                        'log.LogLevel' => 'FINE'
                    ),
                );
                Config::set('paypal', $config);
            }
            $sslcommerz = PaymentMethod::where('unique_code', 'SSLCOMMERZ104')->first();
            if($sslcommerz){
                if(trim($sslcommerz->payment_parameter->environment) == 'live') {
                    $url = "https://securepay.sslcommerz.com";
                    $host = false;
                }else {
                    $url = "https://sandbox.sslcommerz.com";
                    $host = true;
                }
                $sslconfig = array(
                    'projectPath' => env('PROJECT_PATH'),
                    'apiDomain' => env("API_DOMAIN_URL", $url),
                    'apiCredentials' => [
                        'store_id' => $sslcommerz->payment_parameter->store_id,
                        'store_password' => $sslcommerz->payment_parameter->store_password
                    ],
                    'apiUrl' => [
                        'make_payment' => "/gwprocess/v4/api.php",
                        'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
                        'order_validate' => "/validator/api/validationserverAPI.php",
                        'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
                        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
                    ],
                    'connect_from_localhost' => env("IS_LOCALHOST", $host),
                    'success_url' => '/user/success',
                    'failed_url' => '/user/fail',
                    'cancel_url' => '/user/cancel',
                    'ipn_url' => '/user/ipn',
                );
                Config::set('sslcommerz', $sslconfig);
            }
         } catch (\Exception $ex) {

        }
    }
}
