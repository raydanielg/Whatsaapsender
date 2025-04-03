<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use App\Rules\FileExtentionCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class AutomaticPaymentRequest extends FormRequest
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
            'image' => ["nullable",'image', new FileExtentionCheckRule(json_decode(site_settings('mime_types'),true))],
            'currency_code' => [
                'required',
                'in:'.implode(',', array_keys(json_decode(site_settings('currencies'), true)))
            ],
            'percent_charge' => [
                'required'
            ],
            'rate' => [
                'required'
            ]
        ];
        
        return $rules;
    }
}
