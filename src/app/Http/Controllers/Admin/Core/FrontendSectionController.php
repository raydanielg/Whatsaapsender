<?php

namespace App\Http\Controllers\Admin\Core;

use App\Enums\SettingKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\FrontendSectionRequest;
use App\Models\FrontendSection;
use App\Service\Admin\Core\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class FrontendSectionController extends Controller
{
    public $fileService;
    public $frontendSectionItems;
    public function __construct() {

        $this->fileService = new FileService();
        $this->frontendSectionItems = getFrontendSection();
    }


    public function index($section_key, $type = null) {
        
        Session::put("menu_active", true);
        
        $sectionFixedContent    = [];
        $sectionMultiContent    = [];
        $elementContents        = [];
        $section_type           = null;
        
        $sectionData            = Arr::get($this->frontendSectionItems, $section_key, null);
        $formattedSectionKey    = strtolower(textFormat(['-'], $section_key, '_'));

        if (!isset($sectionData)) {
            abort(404);
        }
        
        if(array_key_exists('types', $sectionData)) {

            $section_type = strtolower($type);
            $sectionData = $sectionData['types'][$section_type];
            $sectionFixedContent = FrontendSection::where('section_key', $formattedSectionKey . ".$section_type.fixed_content")->orderBy('id', 'desc')->first();
            $sectionMultiContent = FrontendSection::where('section_key', $formattedSectionKey . ".$section_type.multiple_static_content")->orderBy('id', 'desc')->first();
            $elementContents     = FrontendSection::where('section_key', $formattedSectionKey . ".$section_type.element_content")->orderBy('id')->orderBy('id', 'desc')->get();
        } else {
            $sectionFixedContent = FrontendSection::where('section_key', $formattedSectionKey . '.fixed_content')->orderBy('id', 'desc')->first();
            $sectionMultiContent = FrontendSection::where('section_key', $formattedSectionKey . '.multiple_static_content')->orderBy('id', 'desc')->first();
            $elementContents     = FrontendSection::where('section_key', $formattedSectionKey . '.element_content')->orderBy('id')->orderBy('id', 'desc')->get();
        }
        
        
        $title = Arr::get($sectionData, 'name');
        
        return view('admin.frontend_section.index', compact('sectionData', 'sectionFixedContent', 'elementContents', 'section_key', 'title', 'sectionMultiContent', 'section_type'));
    }
    
    function hasNestedImages(Collection $collection) {
        
        foreach ($collection as $key => $value) {
            if ($key === 'images') {
                return true;
            }
            if (is_array($value) || $value instanceof Collection) {
                if ($this->hasNestedImages(collect($value))) {
                    return true;
                }
            }
        }
        return false;
    }

    public function saveFrontendSectionContent(FrontendSectionRequest $request, $key, $section_type = null) {
        
        $status = 'error';
        $message = 'Something went wrong';
        try {
            $valInputs = $request->except('_token', 'key', 'status', 'content_type', 'id', 'images');
            $inputContentValue = $this->sanitizeInputs($valInputs);
            $type = $request->input('content_type');
            $formattedKey = strtolower(textFormat(['-'], $key, '_'));
            $formattedSectionType = strtolower(textFormat(['_'], $section_type, '-'));

            $imageJson = [];
            if (!$type) abort(404);
            
            $group_image = false;
            
            if($section_type) {
                
                $imageJson = Arr::get($this->frontendSectionItems, "{$key}.types.{$type}.images", []);
            } else {
                $imageJson = Arr::get($this->frontendSectionItems, "{$key}.{$type}.images", []);
                
            }
            
            
            if($type == "element_content") {
                // dd($imageJson, $key, $type, array_key_exists('item_group', Arr::get(getFrontendSection(), "{$key}.{$type}", [])), $this->hasNestedImages(collect(Arr::get(getFrontendSection(), "{$key}.{$type}.item_group", []))), Arr::get(getFrontendSection(), "{$key}.{$type}.item_group", []));    
                if(array_key_exists('item_group', Arr::get($this->frontendSectionItems, "{$key}.{$type}", [])) && $this->hasNestedImages(collect(Arr::get($this->frontendSectionItems, "{$key}.{$type}.item_group", [])))) {

                    foreach(Arr::get($this->frontendSectionItems, "{$key}.{$type}.item_group", []) as $group_image_key => $group_image_value) {

                        $imageJson = $group_image_value['images'];
                        
                        $group_image = true;
                    }
                }
            }

            $validationRules = $this->generateValidationRules($request, $imageJson);
            $request->validate($validationRules, [], ['images' => 'image']);
            
            $inputvalue = $this->processImageInputs($request, $imageJson, $inputContentValue, $formattedKey, $group_image, $section_type);
            
            if($inputvalue == false || !is_array($inputvalue)) {
                
                $message = translate("Could not upload images");
            }
            
            $frontendSection = $this->findOrCreateContent($request, $formattedKey, $type, $section_type);
           
            $sectionValue = [];
            if(!is_null($frontendSection->section_value)) {

                $sectionValue = $frontendSection->section_value;
            }
            
            $frontendSection->section_value = array_replace_recursive($sectionValue, $inputvalue);
            
            $frontendSection->save();
            
            $status = 'success';
            $message = translate("Content has been updated");
           
        } catch(ValidationException $ve) {

            $message = $ve->getMessage();
        } catch(\Exception $e) {
            
            $message = translate("Something went wrong");
        }
        $notify[] = [$status, $message];
        

        if($section_type) {
            return redirect()->route('admin.frontend.sections.index', ['section_key' => $key, 'type' => $formattedSectionType])->withNotify($notify);
        } else {
            return redirect()->route('admin.frontend.sections.index', ['section_key' => $key])->withNotify($notify);
        }
        
    }


    private function processImageInputs(Request $request, array $imgJson, array $inputContentValue, string $key, $item_group = false, $section_type = null) {
        
        if ($imgJson) {

            foreach ($imgJson as $imageKey => $imgValue) {
               
                $file = [];
                if($item_group && $request->has('images')) {

                    foreach($request->file('images') as $group_image_key => $group_image_value) {
                        
                        $file = $group_image_value[$imageKey];
                        if (is_file($file)) {
                            try {
                                $setImageStoreValue = $this->storeImage($imgJson,$request->input('content_type'), $key, $file, $imageKey, $section_type);
                                
                                if($setImageStoreValue == false){
                                    return false;
                                }
                                
                                Arr::set($inputContentValue, "{$group_image_key}.{$imageKey}", $setImageStoreValue);
                                
                            } catch (\Exception $exp) {
                                
                                return false;
                            }
                        }
                    }
                   

                } else {
                    $file = $request->file("images.{$imageKey}");
                    if (is_file($file)) {
                        try {
                            $setImageStoreValue = $this->storeImage($imgJson,$request->input('content_type'), $key, $file, $imageKey, $section_type);
                            
                            if($setImageStoreValue == false){
                                return false;
                            }
                           
                            Arr::set($inputContentValue, $imageKey, $setImageStoreValue);
    
                        } catch (\Exception $exp) {
                            
                            return false;
                        }
                    }
                }
            }
            return $inputContentValue;
        }
        return $inputContentValue;

    }

    protected function storeImage($imgJson, $type, $key, $file, $imgKey, $section_type = null): string {

        
        try {
            
            $path = asset('file.default.jpg');
           
            if ($type == 'fixed_content' || $type == "$section_type.fixed_content") {

                $path = config("setting.file_path.frontend.$imgKey")['path'];
                $size = Arr::get($imgJson, "{$imgKey}.size", );
                
            } elseif($type == 'element_content' || $type == "$section_type.element_content") {
                $path = config("setting.file_path.frontend.element_content.$key.$imgKey")['path'];
               
                $size = Arr::get($imgJson, "{$imgKey}.size", );
                
            } else {
                $path = $path[$key]['path'];
                $size = $path[$key]['size'];
            }

            $deleteFile = $type != SettingKey::ELEMENT_CONTENT->value;
            if($deleteFile && $imgKey == "service_breadcrumb_image") {
                $deleteFile = false;
            }
            return $this->fileService->uploadFIle($file, null, $path, $size, $deleteFile);

        } catch (\Exception $exception) {
            
            return false;
        }
    }

    public function getFrontendSectionElement($section_key, $type = null, $id = null)
    {
        Session::put("menu_active", true);
        $section = $this->frontendSectionItems;
        $section_key = strtolower(textFormat(['_'], $section_key, '-'));
        $sectionData = Arr::get($section, $section_key);
        $section_type = null;
        if (!$sectionData) {
            abort(404);
        }
        if($type && $type != 'type') {
            $section_type = $type;
            $sectionData  = Arr::get($sectionData, "types.{$section_type}");
        }
        $title = translate($sectionData['name'] . ' elements');
        $frontendSectionElement = null;
        if ($id) {
            $frontendSectionElement = FrontendSection::findOrFail($id);
            
            return view('admin.frontend_section.element', compact('section','sectionData','section_key', 'title', 'frontendSectionElement', 'section_type'));
        }
        
        return view('admin.frontend_section.element', compact('section','sectionData', 'section_key', 'title', 'frontendSectionElement', 'section_type'));
    }


    public function delete(Request $request) {

        $request->validate(['element_id' => 'required']);
        $frontendSectionElement = FrontendSection::findOrFail($request->input('element_id'));
        $frontendSectionElement->delete();

        $notify[] = ['success', 'Section element content has been removed.'];
        return back()->withNotify($notify);
    }



    private function sanitizeInputs(array $inputs): array
    {
        $purifier = new \HTMLPurifier();
        $sanitizedInputs = [];
        foreach ($inputs as $keyName => $input) {
            if (is_array($input)) {
                $sanitizedInputs[$keyName] = $input;
            } else {
                $sanitizedInputs[$keyName] = $purifier->purify($input);
            }
        }

        return $sanitizedInputs;
    }

    private function generateValidationRules(Request $request, ?array $imgJson): array
    {
        $validationRules = [];
        foreach ($request->except('_token') as $input => $val) {
            if ($input == "images" && $imgJson) {
                foreach ($imgJson as $key => $imageValue) {
                    $validationRules["images.{$key}"] = ['nullable', 'image'];
                }
            }else {
                $validationRules[$input] = 'required';
            }
        }
        return $validationRules;
    }

    private function findOrCreateContent(Request $request, $key, $type, $section_type = null)
    {
        if ($request->filled('id')) {
            $frontendSection = FrontendSection::findOrFail($request->input('id'));
        } else {
          
            $frontendSection = FrontendSection::where('section_key', "{$key}.{$type}")->first();
           
            if (!$frontendSection || ((strpos($type, 'element_content') != false ))) {
                $frontendSection = new FrontendSection();
                $frontendSection->section_key = "{$key}.{$type}";
                $frontendSection->save();
            } elseif(strpos($type, 'element_content') >= 0) {
                $frontendSection = new FrontendSection();
                $frontendSection->section_key = "{$key}.{$type}";
                $frontendSection->save();
            }
        }
        
        if(strpos($type, 'element_content') === false) {

            FrontendSection::where('section_key', "{$key}.{$type}")
                                ->where("id", "!=" ,$frontendSection->id)
                                ->delete();
        }

        return $frontendSection;
    }


}