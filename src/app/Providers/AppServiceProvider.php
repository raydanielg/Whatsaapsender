<?php

namespace App\Providers;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Models\CommunicationLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Laravel\Passport\Passport;
use App\Models\PaymentLog;
use App\Models\SupportTicket;
use App\Models\Language;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 
use Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $envFile = base_path('.env');
            $envNewVariables = [  
                'ENVATO_PURCHASE_KEY' => '', 
                'WP_SERVER_URL' => 'http://127.0.0.1:3008', 
                'NODE_SERVER_HOST' => '127.0.0.1',
                'NODE_SERVER_PORT' => '3008',
                'MAX_RETRIES' => '5',
                'RECONNECT_INTERVAL' => '5000',
                'NODE_TLS_REJECT_UNAUTHORIZED' => '1',
            ];

            foreach ($envNewVariables as $variable => $value) {
                if (!File::exists($envFile) || !Str::contains(file_get_contents($envFile), $variable . '=')) {
                    File::append($envFile, PHP_EOL . $variable . '=' . $value);
                }
            }
        } catch (Exception $e) {
            
        }

        try {
            DB::connection()->getPdo();
            Passport::routes();
            Paginator::useBootstrap();

            $view['languages'] = Language::all();
            $view['users']     = User::orderBy('id','DESC')->take(7)->get();
            
            if (!Session::has('lang')) {

                $default_language = Language::where('is_default', 1)->first();

                if($default_language){
                    session()->put('lang', $default_language->code);
                    session()->put('flag', $default_language->flag);
                }
            }

            view()->share($view);

            view()->composer('admin.partials.sidebar', function ($view) {
                $view->with([
                    'pending_sms_count'             => CommunicationLog::where('type', ServiceType::SMS->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                    'pending_whatsapp_count'        => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->where('status',CommunicationStatusEnum::PENDING->value)->count(),
                    'pending_email_count'           => CommunicationLog::where('type', ServiceType::EMAIL->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                    'running_support_ticket_count'  => SupportTicket::where('status',1)->count(),
                    'answered_support_ticket_count' => SupportTicket::where('status',2)->count(),
                    'replied_support_ticket_count'  => SupportTicket::where('status',3)->count(),
                    'closed_support_ticket_count'   => SupportTicket::where('status',4)->count(),
                    'pending_manual_payment_count'  => PaymentLog::where('status', (string) StatusEnum::TRUE->status())->count(),
                    'sms_template_request'          => Template::whereNotNull('user_id')->where([
                        'type'   => (string) ServiceType::SMS->value,
                        'status' => (string) StatusEnum::FALSE->status()
                    ])->count(),
                    'email_template_request'        => Template::whereNotNull('user_id')->where([
                        'type'   => (string) ServiceType::EMAIL->value,
                        'status' => (string) StatusEnum::FALSE->status()
                    ])->count(),
                ]);
            });

            view()->composer('user.partials.sidebar', function ($view) {
                $view->with([
                    
                    'replied_support_ticket_count'  => SupportTicket::where('status',3)->count(),
                    'answered_support_ticket_count' => SupportTicket::where('status',2)->count(),
                ]);
            });

            Validator::extend('username_format', function ($attribute, $value, $parameters, $validator) {
                return preg_match('/^[a-z]+(?:_[a-z]+)*$/', $value);
            });
    
            Validator::replacer('username_format', function ($message, $attribute, $rule, $parameters) {
                return str_replace(':attribute', $attribute, 'The :attribute must be in lowercase with underscores.');
            });

        }catch(Exception $ex) {
            
           
        }
    }
}
