<?php

namespace App\Http\Requests;

use App\Rules\FileExtentionCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminProfileRequest extends FormRequest
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
            'name'  => 'required',
            'email' => 'required|email',
            'image' => ["nullable",'image', new FileExtentionCheckRule(json_decode(site_settings('mime_types'),true))]
        ];
        return $rules;
    }
}
