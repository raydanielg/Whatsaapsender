<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Subscription;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomingApiMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = ($request->hasHeader('Api-key')) ? $request->header('Api-key') : null;
       
        if(is_null($apiKey)){
            return response()->json([
                'status' => 'error',
                'error' => 'Invalid Api Key'
            ],403);
        }
        $user = User::where('api_key', $apiKey)->first();
        $admin = Admin::where('api_key', $apiKey)->first();

        if($user){
            $subscription = Subscription::where('user_id',$user->id)->where('status','1')->count();
            if($subscription == 0){
                return response()->json([
                    'status' => 'error',
                    'error' => 'Your Subscription Is Expired! Buy A New Plan'
                ],403);
            }
        }

        if($user || $admin){
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'error' => 'Invalid Api Key'
        ],403);
    }
}
