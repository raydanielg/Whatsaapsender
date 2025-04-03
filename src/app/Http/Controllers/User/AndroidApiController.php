<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AndroidApi;
use App\Models\AndroidApiSimInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AndroidApiController extends Controller
{

    public function store(Request $request) {

        $android_device_count = AndroidApi::where("user_id", auth()->user()->id)->get()->count();
        $access               = planAccess(auth()->user());

        if($access) {

            $access = (object)planAccess(auth()->user());
        } else {

            $notify[] = ['error','Please Purchase A Plan'];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }

        $access = (object)$access->android;
        if($access->is_allowed) {

            if($android_device_count < $access->gateway_limit || $access->gateway_limit == -1) {

                $request->validate([
                    'name'     => ['required', 'username_format', 'unique:android_apis,name'],
                    'password' => 'required|confirmed',
                    'status'   => 'required|in:1,2'
                ], [
                    'name.unique' => 'This name is taken by a user, please try another name',
                ]);

                AndroidApi::create([
                    'name'          => $request->input('name'),
                    'user_id'       => auth()->user()->id,
                    'show_password' => $request->input('password'),
                    'password'      => Hash::make($request->input('password')),
                    'status'        => $request->input('status'),
                ]);
                $notify[] = ['success', 'New Android Gateway has been created'];
                return back()->withNotify($notify);
            } else {

                $notify[] = ['error', "Plan does not allow you to create more than $access->gateway_limit gateways"];
                return back()->withNotify($notify);
            }
        } else {

            $notify[] = ['error', "Current Plan doesn't allow you to add Android Gateway"];
            return back()->withNotify($notify);
        }
    }

    public function update(Request $request) {

        $request->validate([
			'name'     => ['required', 'unique:android_apis,name,' . request()->id],
			'password' => 'required',
			'status'   => 'required|in:1,2'
		], [
			'name.unique' => 'This name is taken by a user, please try another name',
		]);

        $androidApi           = AndroidApi::where('user_id', auth()->user()->id)->where('id', $request->id)->firstOrFail();
        $android_device_count = AndroidApi::where(["user_id" => auth()->user()->id, "status" => 1])->get()->count();
        $access               = planAccess(auth()->user());
        if($access) {

            $access = (object)planAccess(auth()->user());
        } else {

            $notify[] = ['error','Please Purchase A Plan'];
            return redirect()->route('user.dashboard')->withNotify($notify);
        }
        $access = (object)$access->android;
        if($access->is_allowed) {

            if($android_device_count < $access->gateway_limit || $access->gateway_limit == -1) {

                $androidApi->update([
                    'name'          => $request->input('name'),
                    'user_id'       => auth()->user()->id,
                    'show_password' => $request->input('password'),
                    'password'      => Hash::make($request->input('password')),
                    'status'        => $request->input('status'),
                ]);
            }  else {

                $notify[] = ['error', "Plan does not allow you to update more than $access->gateway_limit gateways"];
                return back()->withNotify($notify);
            }
        } else {
            
            $notify[] = ['error', "Current Plan doesn't allow you to add Android Gateway"];
            return back()->withNotify($notify);
        }
        $notify[] = ['success', 'Android Gateway has been updated'];
        return back()->withNotify($notify);
    }

    public function simList($id) {

        $android  = AndroidApi::where('user_id', auth()->user()->id)->where('id', $id)->firstOrFail();
        $title    = ucfirst($android->name).translate(" api gateway sim list");
        $simLists = AndroidApiSimInfo::where('android_gateway_id', $id)->latest()->with('androidGatewayName')->paginate(paginateNumber(site_settings("paginate_number")));
        return view('user.android.sim', compact('title', 'android', 'simLists'));
    }

    public function delete(Request $request) {

        $android = AndroidApi::where('user_id', auth()->user()->id)->where('id', $request->input('id'))->firstOrFail();
        AndroidApiSimInfo::where('android_gateway_id', $android->id)->delete();
        $android->delete();
        $notify[] = ['success', 'Android Gateway has been deleted'];
        return back()->withNotify($notify);
    }

    public function simNumberDelete(Request $request) {

        $simList = AndroidApiSimInfo::where('id', $request->id)->firstOrFail();
        AndroidApi::where('user_id', auth()->user()->id)->where('id', $simList->android_gateway_id)->firstOrFail();
        $simList->delete();
        $notify[] = ['success', 'Android Gateway sim has been deleted'];
        return back()->withNotify($notify);
    }
}
