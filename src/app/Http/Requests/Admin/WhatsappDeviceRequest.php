<?php

namespace App\Http\Requests\Admin;

use App\Rules\DifferenceRule;
use Illuminate\Foundation\Http\FormRequest;

class WhatsappDeviceRequest extends FormRequest
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
            'min_delay' => [
                'required', 
                'gte:10'
            ],
            'max_delay' => [
                'required', 
                'gt:min_delay',
                new DifferenceRule(request()->input('min_delay'))
            ],
        ];
        return $rules;
    }
}
