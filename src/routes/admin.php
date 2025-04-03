<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\Admin\Core\BlogController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Core\AdminController;
use App\Http\Controllers\Admin\Core\ReportController;
use App\Http\Controllers\Admin\Core\SettingController;
use App\Http\Controllers\Admin\Core\LanguageController;
use App\Http\Controllers\Admin\Core\CurrencyController;
use App\Http\Controllers\Admin\Core\CustomerController;
use App\Http\Controllers\UpgradeVersionMigrateController;
use App\Http\Controllers\Admin\Contact\ContactController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Core\GlobalWorldController;
use App\Http\Controllers\Admin\Core\PricingPlanController;
use App\Http\Controllers\Admin\Dispatch\CampaignController;
use App\Http\Controllers\Admin\Template\TemplateController;
use App\Http\Controllers\Admin\Gateway\SmsGatewayController;
use App\Http\Controllers\Admin\Gateway\AndroidApiController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Contact\ContactGroupController;
use App\Http\Controllers\Admin\Core\FrontendSectionController;
use App\Http\Controllers\Admin\Gateway\EmailGatewayController;
use App\Http\Controllers\Admin\Ticket\SupportTicketController;
use App\Http\Controllers\Admin\Gateway\WhatsappDeviceController;
use App\Http\Controllers\Admin\Payment\PaymentGatewayController;
use App\Http\Controllers\Admin\Dispatch\CommunicationController;
use App\Http\Controllers\Admin\Contact\ContactSettingsController;
use App\Http\Controllers\Admin\Gateway\WhatsappCloudApiController;

Route::middleware([
            'upgrade'
        ])->prefix('admin')
            ->name('admin.')
            ->group(function () {

    
    // Route::prefix('update')
    //         ->name('update.')
    //         ->group(function () {

    //     Route::resource('/', UpgradeVersionMigrateController::class)
    //             ->only([
    //                 'index', 
    //                 'store'
    //             ])->names([
    //                 'index'     => 'index',
    //                 'store'     => 'save'
    //             ]);
            
    //     Route::post('status/update', [UpgradeVersionMigrateController::class, 'statusUpdate'])
    //             ->name('status.update');
    // });


    Route::controller(UpgradeVersionMigrateController::class)
            ->prefix('update/')
            ->name('update.')
            ->group(function () { 

        Route::get('verify', 'verify')->name('verify');
        Route::post('verify', 'store')->name('verify.store');
        Route::get('index', 'index')->name('index');
        Route::get('version', 'update')->name('version');
    });
    
    //Admin Login
    Route::controller(LoginController::class)
            ->group(function () {

        Route::get('/', 'showLogin')->name('login');
        Route::post('authenticate', 'authenticate')->name('authenticate');
        Route::get('logout', 'logout')->name('logout');
    });

    //Admin Forgot Passsword 
    Route::controller(NewPasswordController::class)
            ->group(function () {

        Route::get('forgot-password', 'create')->name('password.request');
        Route::post('password/email', 'store')->name('password.email');
        Route::get('password/verify/code', 'passwordResetCodeVerify')->name('password.verify.code');
        Route::post('password/code/verify', 'emailVerificationCode')->name('email.password.verify.code');
    });

    //Admin Reset Passsword 
    Route::controller(ResetPasswordController::class)
            ->group(function () {

        Route::get('reset-password/{token}', 'create')->name('password.reset');
        Route::post('reset/password', 'store')->name('password.reset.update');
    });

    Route::middleware([
            'admin',
            'demo.mode',
            'sanitizer'
            ])->group(function () {

        Route::post('/verify-email', [GlobalWorldController::class, 'verifyEmail'])
                ->name('verify.email');

        ## Contact Management
        Route::prefix('contacts')
                ->name('contact.')
                ->group(function () {

            # Contact Settings Routes
            Route::prefix('settings')
                    ->name('settings.')
                    ->group(function () {

                Route::resource('/', ContactSettingsController::class, [
                    'parameters' => [
                        '' => 'attribute_name?'
                    ], 
                ])->only([
                    'index', 
                    'create', 
                    'store', 
                    'destroy'
                ])->names([
                    'index'     => 'index',
                    'create'    => 'create',
                    'store'     => 'save',  
                    'destroy'   => 'delete',
                ]);
                    
                Route::post('status/update', [ContactSettingsController::class, 'statusUpdate'])
                        ->name('status.update');
            });

            # Contact Groups 
            Route::prefix('groups')
                    ->name('group.')
                    ->group(function () {

                Route::resource('/', ContactGroupController::class, [
                    'parameters' => [
                        '' => 'uid?'
                    ],
                ])->only([
                    'index', 
                    'store', 
                    'update', 
                    'destroy'
                ]);
        
                Route::controller(ContactGroupController::class)->group(function () {
                    Route::post('status/update', 'updateStatus')->name('status.update');
                    Route::post('bulk/action', 'bulk')->name('bulk');
                    Route::post('fetch/{type?}', 'fetch')->name('fetch');
                });
            });
            
            # Contacts
            Route::resource('/', ContactController::class, [
                'parameters' => ['' => 'uid?'], 
            ])->only([
                'index', 
                'create', 
                'store', 
                'update', 
                'destroy'
            ]);
        
            Route::controller(ContactController::class)->group(function () {

                Route::get('index/{group_id?}', 'index')->name('index'); 
                Route::get('create/{group_id?}', 'create')->name('create.with_group'); 
                Route::post('status/update', 'updateStatus')->name('status.update');
                Route::post('bulk/action', 'bulk')->name('bulk');
                Route::post('upload/file', 'uploadFile')->name('upload.file');
                Route::post('delete/file', 'deleteFile')->name('delete.file');
                Route::post('parse/file', 'parseFile')->name('parse.file');
                Route::get('demo/file/{type?}', 'demoFile')->name('demo.file');
                Route::post('update/email/verification', 'singleEmailVerification')->name('update.email.verification');
                Route::post('export/{group_id?}', 'exportContacts')->name('export');
            });
        });
        
        //Admin Panel 
        Route::controller(AdminController::class)->group(function () {

            //Dashboard
            Route::get('dashboard', 'dashboard')->name('dashboard');

            //Admin Account
            Route::get('profile', 'profile')->name('profile');
            Route::post('profile/update', 'profileUpdate')->name('profile.update');
            Route::post('password/update', 'passwordUpdate')->name('password.update');
        });

        //Manage Customer
        Route::controller(CustomerController::class)->prefix('user/')->name('user.')->group(function () {

            Route::get('', 'index')->name('index');
            Route::get('active/', 'index')->name('active');
            Route::get('banned/', 'index')->name('banned');
            Route::get('detail/{uid}', 'details')->name('details');
            Route::get('login/{uid}', 'login')->name('login');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('store', 'store')->name('store');
            Route::post('modify/credit', 'modifyCredit')->name('modify.credit');
        });

        //Manage Membership Plans
        Route::controller(PricingPlanController::class)->prefix('membership/plan/')->name('membership.plan.')->group(function() {

            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update', 'update')->name('update');
            Route::post('delete', 'delete')->name('delete');
            Route::post('status/update/', 'statusUpdate')->name('status.update');
            Route::post('bulk/action/', 'bulk')->name('bulk');
        });

        













        // Manage Frontend Section 
        Route::controller(FrontendSectionController::class)->prefix('frontend/section/')->name('frontend.sections.')->group(function () {
            
            Route::get('{section_key}/{type?}', 'index')->name('index');
            Route::post('/save/content/{section_key}/{type?}', 'saveFrontendSectionContent')->name('save.content');
            Route::get('/element/content/{section_key}/{type?}/{id?}', 'getFrontendSectionElement')->name('element.content');
            Route::post('/element/delete/', 'delete')->name('element.delete');
        });

        //Settings
        Route::prefix('system/')->name('system.')->group(function () {

            Route::controller(SystemController::class)->group(function() {

                Route::get('/cache/clear', 'cacheClear')->name('cache.clear');
                Route::get("info/", 'systemInfo')->name('info');
            });
            
            Route::controller(SettingController::class)->group(function() {

                Route::get('setting/{type?}', 'index')->name('setting');
                Route::post('setting/store', 'store')->name("setting.store");
            });

            Route::controller(CurrencyController::class)->prefix('currency/')->name('currency.')->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('active', 'index')->name('active');
                Route::get('inactive', 'index')->name('inactive');
                Route::post('/save', 'save')->name('save');
                Route::post('/status/update', 'statusUpdate')->name('status.update');
                Route::post('/delete', 'delete')->name('delete');
            });
    
            Route::controller(LanguageController::class)->prefix('language/')->name('language.')->group(function () {
    
                Route::get('', 'index')->name('index');
                Route::get('translate/{code?}', 'translate')->name('translate');
                Route::post('store', 'store')->name('store');
                Route::post('update', 'update')->name('update');
                Route::post('delete', 'languageDelete')->name('delete');
                Route::post('/status/update', 'statusUpdate')->name('status.update');

                Route::prefix('data/')->name('data.')->group(function() {

                    Route::post('update', 'languageDataUpdate')->name('update');
                    Route::post('delete', 'languageDataDelete')->name('delete');
                });
            });

            Route::controller(GlobalWorldController::class)->prefix('spam/word/')->name('spam.word.')->group(function () {
                Route::get('', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::post('update', 'update')->name('update');
                Route::post('delete', 'delete')->name('delete');
            });
        });
        
        //Support Ticket
        Route::prefix('support/')->name('support.')->group(function () {

            Route::controller(SupportTicketController::class)->prefix('ticket/')->name('ticket.')->group(function() {

                Route::get('/', 'index')->name('index');
                Route::get('closed', 'index')->name('closed');
                Route::get('running', 'index')->name('running');
                Route::get('replied', 'index')->name('replied');
                Route::get('answered', 'index')->name('answered');

                Route::prefix('priority/')->name('priority.')->group(function () {
                    
                    Route::get('high', 'index')->name('high');
                    Route::get('medium', 'index')->name('medium');
                    Route::get('low', 'index')->name('low');
                });
                
                Route::post('reply/{id}', 'ticketReply')->name('reply');
                Route::post('closed/{id}', 'closedTicket')->name('closeds');
                Route::get('details/{id}', 'ticketDetails')->name('details');
                Route::get('download/{id}', 'supportTicketDownload')->name('download');
            });
        });

        //Report and logs
        Route::controller(ReportController::class)->prefix('report')->name('report.')->group(function() {

            Route::prefix('record/')->name("record.")->group(function() {

                Route::get('transaction', 'transaction')->name('transaction');
                Route::get('subscription', 'subscription')->name('subscription');
                Route::get('payment', 'paymentLog')->name('payment');
            });

            Route::prefix('credit/')->name("credit.")->group(function() {

                Route::get('sms/', 'credit')->name('sms');
                Route::get('whatsapp/', 'credit')->name('whatsapp');
                Route::get('email/', 'credit')->name('email');
            });
            
            Route::get('payment/detail/{id}', 'paymentDetail')->name('payment.detail');
            Route::post('payment/approve', 'approve')->name('payment.approve');
            Route::post('payment/reject', 'reject')->name('payment.reject');
        });

        //Payment Gateways
        Route::controller(PaymentGatewayController::class)->prefix('payment/')->name('payment.')->group(function() {

            Route::get('automatic/index', 'index')->name('automatic.index');
            Route::get('manual/index', 'index')->name('manual.index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}/{slug?}', 'edit')->name('edit');
            Route::post('/status/update', 'statusUpdate')->name('status.update');
            Route::post('automatic/update/{id}', 'automaticUpdate')->name('automatic.update');
            Route::post('manual/update/{id}', 'manualUpdate')->name('manual.update');
            Route::post('delete', 'delete')->name('delete');
        });

        //Messaging Gateways
        Route::prefix('gateway/')->name('gateway.')->group(function() {

            //SMS Gateways
            Route::prefix('sms/')->name('sms.')->group(function() {

                //Android Gateways
                Route::controller(AndroidApiController::class)->prefix('android/')->name('android.')->group(function() {
                    
                    Route::get('index', 'index')->name('index');
                    Route::post('store', 'store')->name('store');
                    Route::post('update', 'update')->name('update');
                    Route::post('/status/update', 'statusUpdate')->name('status.update');
                    Route::post('delete/', 'delete')->name('delete');
                    Route::post('/bulk/action','bulk')->name('bulk');
                    Route::prefix('link/')->name('link.')->group(function() {

                        Route::post('store', 'linkStore')->name('store');
                    });
                    Route::prefix('sim/')->name('sim.')->group(function() {

                        Route::get('list/{id?}', 'simList')->name('index');
                        Route::post('/bulk/action','simBulk')->name('bulk');
                        Route::post('delete/', 'simNumberDelete')->name('delete');
                    });
                });

                //API Gateways
                Route::controller(SmsGatewayController::class)->prefix('api/')->name('api.')->group(function () {

                    Route::get('index', 'index')->name('index');
                    Route::post('/status/update', 'statusUpdate')->name('status.update');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('store', 'store')->name('store');
                    Route::post('update', 'update')->name('update');
                    Route::post('/bulk/action','bulk')->name('bulk');
                });
            });

            //WhatsApp Gateways
            Route::prefix('whatsapp/')->name('whatsapp.')->group(function() {
                
                Route::controller(WhatsappDeviceController::class)->prefix('device/')->name('device')->group(function() {

                    Route::get('', 'index');
                    Route::post('save', 'save')->name('.save');
                    Route::post('status/update', 'statusUpdate')->name('.status.update');
                    Route::post('delete', 'delete')->name('.delete');
                    
                    Route::prefix('server/')->name('.server.')->group(function() {

                        Route::post('update', 'updateServer')->name('update');
                        Route::post('qr-code', 'whatsappQRGenerate')->name('qrcode');
                        Route::post('status', 'getDeviceStatus')->name('status');
                    });
                });
                
                Route::controller(WhatsappCloudApiController::class)->prefix('cloud/api')->name('cloud.api')->group(function() {

                    Route::get('{id?}', 'index');
                    Route::post('save', 'save')->name('.save');
                    Route::post('status/update', 'statusUpdate')->name('.status.update');
                    Route::post('delete', 'delete')->name('.delete');
                });
            });

            //Email Gateways
            Route::controller(EmailGatewayController::class)->prefix('email/')->name('email.')->group(function() {
                
                Route::get('index', 'index')->name('index');
                Route::post('test', 'testGateway')->name('test');
                Route::post('store', 'store')->name('store');
                Route::post('update', 'update')->name('update');
                Route::post('delete', 'delete')->name('delete');
                Route::post('status/update', 'statusUpdate')->name('status.update');
            });
        });

        //Templates
        Route::controller(TemplateController::class)->prefix('template/')->name('template.')->group(function() {

            Route::prefix('sms/')->name('sms')->group(function() {

                Route::get('', 'index');
                Route::get('user', 'index')->name('.user');
            });
            Route::prefix('email/')->name('email')->group(function() {

                Route::get('', 'index');
                Route::get('create', 'createEmailTemplate')->name('.create');
                Route::get('edit/{id?}', 'editEmailTemplate')->name('.edit');
                Route::get('edit/json/{id?}', 'editTemplateJson')->name('.edit.json');
                Route::get('get/{id?}', 'templateJson')->name('.get');
                Route::get('user', 'index')->name('.user');
                Route::get('fetch', 'emailTemplates')->name('.fetch');
            });
            Route::get('whatsapp/{id?}', 'index')->name('whatsapp.index');
            Route::get('refresh', 'refresh')->name('refresh');
            Route::post('save', 'save')->name('save');
            Route::post('status/update', 'statusUpdate')->name('status.update');
            Route::post('delete', 'delete')->name('delete');
            Route::get('fetch/{type?}', 'fetch')->name('fetch');
        });
        












        













        Route::controller(CommunicationController::class)->prefix('communication/')->name('communication.')->group(function() {

            Route::get('api', 'api')->name('api');
            Route::prefix('sms/')->name('sms.')->group(function() {

                Route::get('index/{campaign_id?}', 'smsList')->name('index');
                Route::get('create', 'createSms')->name('create');
                Route::controller(CampaignController::class)->prefix('campaign/')->name('campaign.')->group(function() {

                    Route::get('index', 'index')->name('index');
                    Route::get('create', 'createSms')->name('create');
                    Route::post('save/{type?}', 'saveSms')->name('save');
                    Route::get('edit/{id?}', 'editSms')->name('edit');
                    Route::post('/bulk/action','bulk')->name('bulk');
                    Route::post('delete', 'delete')->name('delete');
                });
            });
            Route::prefix('whatsapp/')->name('whatsapp.')->group(function() {

                Route::get('index/{campaign_id?}', 'whatsappList')->name('index');
                Route::get('create', 'createWhatsapp')->name('create');
                Route::controller(CampaignController::class)->prefix('campaign/')->name('campaign.')->group(function() {

                    Route::get('index', 'index')->name('index');
                    Route::get('create', 'createWhatsapp')->name('create');
                    Route::get('edit/{id?}', 'editWhatsapp')->name('edit');
                    Route::post('save/{type?}', 'saveWhatsapp')->name('save');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('/bulk/action','bulk')->name('bulk');
                });
            });
            Route::prefix('email/')->name('email.')->group(function() {

                Route::get('index/{campaign_id?}', 'emailList')->name('index');
                Route::get('create', 'createEmail')->name('create');
                Route::get('view/{id}', 'viewEmailBody')->name('view');
                Route::controller(CampaignController::class)->prefix('campaign/')->name('campaign.')->group(function() {

                    Route::get('index', 'index')->name('index');
                    Route::get('unsubscriptions/{type?}', 'unsubscriptions')->name('unsubscriptions');
                    Route::post('save/{type?}', 'saveEmail')->name('save');
                    Route::get('create', 'createEmail')->name('create');
                    Route::get('edit/{id?}', 'editEmail')->name('edit');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('/bulk/action','bulk')->name('bulk');
                });
            });
            Route::post('store/{type?}', 'store')->name('store');
            Route::post('delete', 'delete')->name('delete');
            Route::post('status/update/{type?}', 'statusUpdate')->name('status.update');
            Route::post('/bulk/action/{type?}','bulk')->name('bulk');
        });

        Route::controller(BlogController::class)->prefix('blog/')->name('blog.')->group(function() {

            Route::get("index/", "index")->name("index");
            Route::get("create", "create")->name("create");
            Route::get("edit/{uid}", "edit")->name("edit");
            Route::post("save", "save")->name("save");
            Route::post("delete", "delete")->name("delete");
            Route::post('status/update', 'statusUpdate')->name('status.update');
            Route::post('/bulk/action','bulk')->name('bulk');
        });
    });
});