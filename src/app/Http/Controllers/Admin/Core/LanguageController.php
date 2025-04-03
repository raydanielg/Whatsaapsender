<?php

namespace App\Http\Controllers\Admin\Core;

use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Service\Admin\Core\LanguageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Admin\LanguageRequest;
use Illuminate\Validation\ValidationException;

class LanguageController extends Controller
{
    public $languageService;
    public function __construct() {

        $this->languageService = new LanguageService();
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index(): View {

        Session::put("menu_active", true);
        $title     = translate("Manage language");
        $languages = Language::search(['name'])
                                ->latest()
                                ->date()
                                ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                                ->appends(request()->all());
        $countries = json_decode(file_get_contents(resource_path(config('constants.options.country_code')) . 'countries.json'),true);
        return view('admin.language.index', compact('title', 'languages','countries'));
    }

    /**
     *
     * @param LanguageRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function store(LanguageRequest $request) {

        $notify = $this->languageService->store($request);
        return back()->withNotify($notify);
    }

    /**
     *
     * @param LanguageRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function update(LanguageRequest $request) {

        $status  = 'success';
        $message = translate("Language has been updated");

        try {
    
            $language = Language::findOrFail($request->input('id'));
            $language->update([

                'name' => $request->input('name'),
                'ltr'  => $request->input('ltr')
            ]);
           
        } catch (\Exception $e) {

            $status  = 'error';
            $message = translate("Something went wrong");
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws ValidationException If the validation fails.
     * 
     */
    public function statusUpdate(Request $request) {
        
        try {
            
            $this->validate($request,[

                'id'     => 'required',
                'value'  => 'required',
                'column' => 'required',
            ]); 

            $notify = $this->languageService->statusUpdate($request);
            return $notify;

        } catch (ValidationException $validation) {

            return json_encode([
                'status'  => false,
                'message' => $$validation->errors()
            ]);
        } 
    }

    /**
     *
     * @param Request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function languageDelete(Request $request) {
        
        $this->validate($request, [
            'id' => 'required'
        ]);
        $notify = $this->languageService->destory($request->input('id'));
        return back()->withNotify($notify);
    }

    /**
     * 
     * @return \Illuminate\View\View
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function translate($code = null) {

        Session::put("menu_active", true);
        $language     = Language::where('code',$code)->first();
        $title        = translate("Update: ").$language->name.translate(" language keywords");
        $translations = $this->languageService->translationVal($code);
        return view('admin.language.edit', compact('title', 'translations', 'language', 'code'));
    }

    public function languageDataUpdate(Request $request) {

        $notify = $this->languageService->translateLang($request);
        return $notify;
    }

    /**
     * @throws ValidationException
     */
    public function languageDataDelete(Request $request) {
        
        $this->validate($request, [
            'uid' => 'required'
        ]);
        $notify = $this->languageService->destoryKey($request->input('uid'));
        return back()->withNotify($notify);
    }
}
