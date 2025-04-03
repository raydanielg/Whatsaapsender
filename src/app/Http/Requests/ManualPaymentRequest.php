<?php

namespace App\Http\Requests;

use App\Rules\FileExtentionCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class ManualPaymentRequest extends FormRequest
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
            'name'  => ["required", 'unique:payment_methods,name,'.request()->id],
            'image' => ["nullable",'image', new FileExtentionCheckRule(json_decode(site_settings('mime_types'),true))],
            'currency_code' => [
                'required',
                'in:'.implode(',', array_keys(json_decode(site_settings('currencies'), true)))
            ],
            'percent_charge' => [
                'required',
                'numeric'
            ],
            'rate' => [
                'required',
                'numeric'
            ],
            'field_name' => [
            'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (count($value) !== count(array_unique($value))) {
                        $fail('The ' . textFormat(['_'], $attribute, ' ') . ' field contains duplicate values.');
                    }
                }
            ],
            'field_type' => [
                'required',
                'array',
                'min:1'
            ],
        ];
        
        return $rules;
    }
}
