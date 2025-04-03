<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Admin\Core\GlobalWorldController;

Route::get('queue-work', function () {

    if (Session::get('queue_restart', true)) {
        \Illuminate\Support\Facades\Artisan::call('queue:restart');
        Session::forget('queue_restart');
    }
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
    Illuminate\Support\Facades\Artisan::call('whatsapp:send');
    Illuminate\Support\Facades\Artisan::call('email:send');
    Illuminate\Support\Facades\Artisan::call('sms:send');
  
})->name('queue.work');

Route::get('cron/run', [CronController::class, 'run'])->name('cron.run');

Route::controller(WebController::class)->middleware(['redirect.to.login'])->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('service/{type?}', 'service')->name('service');
    Route::get('blog/search', 'blogSearch')->name('blog.search');
    Route::get('blog/{uid?}', 'blog')->name('blog');
    Route::get('about/', 'about')->name('about');
    Route::get('pricing/', 'pricing')->name('pricing');
    Route::get('contact/', 'contact')->name('contact');
    Route::post('contact/', 'getInTouch')->name('contact.get_in_touch');
    Route::get('/pages/{key}/{id}', 'pages')->name('page');
});


Route::controller(FrontendController::class)->group(function() {

    Route::get('/default/image/{size}', 'defaultImageCreate')->name('default.image');
    Route::get('email/contact/demo/file', 'demoImportFile')->name('email.contact.demo.import');
    Route::get('sms/demo/import/file', 'demoImportFilesms')->name('phone.book.demo.import.file');
    Route::get('demo/file/download/{extension}/{type}', 'demoFileDownloader')->name('demo.file.download');
    Route::get('api/document', 'apiDocumentation')->name('api.document');
});

Route::get('/default-captcha/{randCode}', [HomeController::class, 'defaultCaptcha'])->name('captcha.genarate');
Route::any('/webhook', [WebhookController::class, 'postWebhook'])->name('webhook');
Route::any('/facebook/login', [MetaController::class, 'facebookLogin'])->name('facebook.login');
Route::get('/language/change/{lang?}', [GlobalWorldController::class, 'languageChange'])->name('language.change');

Route::get('/unsubscribe', [HomeController::class, 'unsubscribe'])->name('unsubscribe');
Route::get('/unsubscribe/success', [HomeController::class, 'unsubscribeSuccess'])->name('unsubscribe.success');



