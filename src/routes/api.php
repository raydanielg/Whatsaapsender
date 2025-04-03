<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\AndroidApiSmsController;
use App\Http\Controllers\Api\IncomingApi\EmailController;
use App\Http\Controllers\Api\IncomingApi\SmsController;
use App\Http\Controllers\Api\IncomingApi\WhatsAppController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', [PassportAuthController::class, 'login']);
Route::get('init', [AndroidApiSmsController::class, 'init']);

Route::middleware('auth:api')->group(function () {

    Route::post('configure/sim', [AndroidApiSmsController::class, 'configureSim']);
    Route::post('sms/logs', [AndroidApiSmsController::class, 'smsfind']);
    Route::post('sms/status/update', [AndroidApiSmsController::class, 'smsStatusUpdate']);
    Route::post('sim/status/update', [AndroidApiSmsController::class, 'simClosed']);
});


Route::middleware('incoming.api', 'sanitizer')->name('incoming.')->group(function () {

    Route::post('email/send', [EmailController::class, 'store'])->name('email.send');
    Route::get('email/send', [EmailController::class, 'sendWithQuery'])->name('email.send.query');
    Route::get('get/email/{uid?}', [EmailController::class, 'getEmailLog']);

    Route::post('sms/send', [SmsController::class, 'store'])->name('sms.send');
    Route::get('sms/send', [SmsController::class, 'sendWithQuery'])->name('sms.send.query');
    Route::get('get/sms/{uid?}', [SmsController::class, 'getSmsLog']);

    Route::post('whatsapp/send', [WhatsAppController::class, 'store'])->name('whatsapp.send');
    Route::get('whatsapp/send', [WhatsAppController::class, 'sendWithQuery'])->name('whatsapp.send.query');
    Route::get('get/whatsapp/{uid?}', [WhatsAppController::class, 'getWhatsAppLog']);
});