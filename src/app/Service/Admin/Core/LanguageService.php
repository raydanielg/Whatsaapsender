<?php
namespace App\Service\Admin\Core;

use App\Enums\StatusEnum;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Str;

class LanguageService
{
    public function store($request) :array {
        
        $status  = 'success';
        $message = translate("Language has been added");
        try {

            $country = explode("//", $request->name);
            $code    = strtolower($country[1]);;

            if(Language::where('name', $country[0])->exists()) {

                $status  = "error"; 
                $message = translate('The Language is already added for that country');
            } else {
                
                $language = Language::create([
                    'name'       => $country[0],
                    'ltr'        => $request->input('ltr'),
                    'code'       => $code,
                    'is_default' => StatusEnum::FALSE->status(),
                ]);
    
                $translations = Translation::where('code', 'us')->get();
    
                $translationsToCreate = [];
    
                foreach ($translations as $k) {
                    $translationsToCreate[] = [
                        "uid"   => Str::random(40),
                        'code'  => $language->code,
                        'key'   => $k->key,
                        'value' => $k->value
                    ];
                }
                Translation::insert($translationsToCreate);
            }
        } catch (\Exception $e) {
           
            $status  = 'error';
            $message = translate("Something went wrong");
        }
        $notify[] = [$status, $message];
        return $notify;
    }

    public function translationVal(string $code) :mixed {

        return Translation::where('code',$code) 
                            ->search(['value'])
                            ->orderBy('key', 'asc')
                            ->paginate(paginateNumber(site_settings("paginate_number")))
                            ->appends(request()->all());
    }

    public function translateLang($request) {
       
        $status  = true;
        $message = translate("Language key updated successfully");
        $reload  = false;
        try {
            Translation::where('uid',$request->data['uid'])->update([
                'value' => $request->data['value']
            ]);
            optimize_clear();

        } catch (\Exception $e) {
            
            $status = false;
            $message = $e->getMessage();
        }
        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message
        ]);
    }

    public function destory(int | string $id) :array {

        $status  = 'success';
        $message = translate("Language has been deleted");
        
        try {
            $language = Language::where('id',$id)->first();
            if( $language->code == 'us' || $language->is_default == StatusEnum::TRUE) {

                $status  = "error";
                $message = translate('Default & English Language Can Not Be Deleted');
            }
            else {
                Translation::where("code",$language->code)->delete();
                $language->delete();
                optimize_clear();
            }

        } catch (\Exception $e) {
            
            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return $notify;
    }

    public function destoryKey(int | string $uid):array {

        $status  = 'success';
        $message = translate("Language key has been deleted");

        try {
            $transData = Translation::where('uid',$uid)->first();
            $transData->delete();
            optimize_clear();

        } catch (\Throwable $th) {

            $status  = 'error';
            $message = translate('Post Data Error !! Can Not Be Deleted');
        }
        $notify[] = [$status, $message];
        return $notify;
    }

    public function statusUpdate($request) {
        
        try {
            $status   = true;
            $reload   = false;
            $message  = translate('Default language changed successfully');
            $language = Language::where("id",$request->input('id'))->first();
            $column   = $request->input("column");
            
            if($column != "is_default" && $request->value == StatusEnum::TRUE->status()) {
                
                $language->status = StatusEnum::FALSE->status();
                $language->update();

            } elseif($column != "is_default" && $request->value == StatusEnum::FALSE->status()) {

                $language->status = StatusEnum::TRUE->status();
                $language->update();

            } elseif($column == "is_default") {
                
                $reload = true;
                Language::where('id', '!=',$request->id)->update(["is_default" => StatusEnum::FALSE->status()]);
                $language->$column = StatusEnum::TRUE->status();
                $language->update();
                session(['locale' => $language->code]);
            } else {

                $status = false;
                $message = translate("Something went wrong while updating this language");
            }

        } catch (\Exception $error) {

            $status  = false;
            $message = $error->getMessage();
        }

        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message
        ]);
    }
}
