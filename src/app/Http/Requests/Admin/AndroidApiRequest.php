<?php

namespace App\Http\Requests\Admin;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class AndroidApiRequest extends FormRequest
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
                'username_format', 
                'unique:android_apis,name,'.request()->input('id')
            ],
            'password' => [
                'required', 
            ],
        ];

        if (request()->routeIs('*.gateway.sms.android.update')) {
            $rules['id'] = ['required', 'exists:android_apis,id'];
        } else {
            $rules['password'] = ['confirmed'];
        }
        
        return $rules;
    }
}
