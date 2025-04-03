<?php

namespace App\Http\Requests\Admin;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => [
                'required', 
                'max:255', 
                'unique:languages,name,'.request()->input('id')
            ],
            'ltr' => [

                'required', 
                'in:' . StatusEnum::TRUE->status() . ',' . StatusEnum::FALSE->status()
                
            ],
        ];

        if(request()->routeIs('admin.language.update')) {

            $rules['id'] = ["required",'exists:languages,id'];
        }
        return $rules;
    }
}
