<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageDataRequest extends FormRequest
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
            'key'   => 'required',
            'value' => 'required'
        ];

        if(request()->routeIs('admin.language.update')) {

            $rules['id'] = ["required",'exists:languages,id'];
        }
        return $rules;
    }
}
