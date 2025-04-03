<?php

namespace App\Http\Controllers\Admin\Core;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Service\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

class GlobalWorldController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        Session::put("menu_active", true);
        $title = translate('Spam Word');
        $path = base_path('lang/globalworld/offensive.json');
        $offensiveData = [];

        if(file_exists($path)){
            $offensiveData = json_decode(file_get_contents($path), true);
        }

        return view('admin.global_world.index', compact('title', 'offensiveData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);

        $path = base_path('lang/globalworld/');
        $fileName = 'offensive.json';

        if(!file_exists($path)){
            mkdir($path, 0777, true);
            $location = $path. $fileName;
            File::put($location ,'{}');
        }

        $offensiveData = json_decode(file_get_contents($path.$fileName), true);

        if(!array_key_exists($request->input('key'), $offensiveData)){
            $offensiveData += [$request->input('key') => $request->input('value')];
            File::put($path.$fileName, json_encode($offensiveData));
            $notify[] = ['success', 'Word Added successfully'];
        } else{
            $notify[] = ['error', 'Word Already Exist'];
        }

        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $path = base_path('lang/globalworld/offensive.json');
        $offensiveData = json_decode(file_get_contents($path), true);

        if(array_key_exists($request->input('key'), $offensiveData)){
            $offensiveData[$request->input('key')] = $request->input('value');
            File::put($path, json_encode($offensiveData));

            $notify[] = ['success', 'Word Updated successfully'];
        } else{
            $notify[] = ['error', 'Word Does not  exist'];
        }

    	return back()->withNotify($notify);
    }

    public function delete(Request $request)
    {
        $path = base_path('lang/globalworld/offensive.json');
        $offensiveData = json_decode(file_get_contents($path), true);

        if(in_array($offensiveData[$request->input('id')], $offensiveData)){
            unset($offensiveData[$request->input('id')]);
            File::put($path, json_encode($offensiveData));
        }

        $notify[] = ['success', 'Word Deleted successfully'];
    	return back()->withNotify($notify);
    }


    /**
     * @param  string $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function languageChange($id = null) {
        
        $locale = 'us';
        $language = Language::where('id', $id)->first();
        
        if($language) {

            $locale = $language->code;
            Language::where('is_default', StatusEnum::TRUE->status())->update(['is_default' => StatusEnum::FALSE->status()]);
            $language->is_default = StatusEnum::TRUE->status();
            $language->save();
        }
        session(['locale' => $locale]);
        $notify[] = ['success', 'Language set to '.$language->name];
        return back()->withNotify($notify);
    }

    /**
     * verifyEmail
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function verifyEmail(Request $request):JsonResponse {
        
        $mailService    = new MailService();
        $email          = $request->input('email');
        $result         = $mailService->verifyEmail($email);
        $status         = Arr::get($result, "valid", false);
        $message        = $mailService->processMailVerificationMessage($result);
        
        return response()->json([
            'status'  => $status,
            'message' => $message
        ]);
    }
}
