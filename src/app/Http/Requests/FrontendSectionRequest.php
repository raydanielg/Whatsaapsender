<?php

namespace App\Http\Requests;

use App\Rules\FileExtentionCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class FrontendSectionRequest extends FormRequest
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
        $rules = [];
        if($this->content_type == 'element_content') {

            $rules = [
                'images.*.*' => ["nullable", 'image', new FileExtentionCheckRule(json_decode(site_settings('mime_types'),true))]
            ];
        } else {
            $rules = [
                'images.*' => ["nullable", 'image', new FileExtentionCheckRule(json_decode(site_settings('mime_types'),true))]
            ];
        }
        return $rules;
    }
}
