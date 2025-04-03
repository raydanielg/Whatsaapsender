<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AndroidApi;
use App\Http\Utility\Api\ApiJsonResponse;
use Illuminate\Support\Facades\Auth;

class PassportAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'name'     => $request->name,
            'password' => $request->password
        ];

        if (Auth::guard('android_api')->attempt($credentials)) {
            $user = Auth::guard('android_api')->user();

            if ($user->status == StatusEnum::TRUE->status()) {
                $data = [
                    'token'              => $user->createToken('bluk_sms_token')->accessToken,
                    'android_gateway_id' => $user->id,
                ];
                return ApiJsonResponse::success("Log in successful.", $data);
            } else {
                
                Auth::guard('android_api')->logout();
                return ApiJsonResponse::notFound();
            }
        } else {
            return ApiJsonResponse::notFound();
        }
    }
}