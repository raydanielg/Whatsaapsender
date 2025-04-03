<?php

namespace App\Http\Requests\Admin;

use App\Rules\UniqueArrayKey;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
    public function rules() {
        
        $currencies = json_decode(site_settings("currencies"), true);
        $rules = [
            'code'    => ['required', 'max:20', new UniqueArrayKey($currencies, $this->input('old_code'), $this->input('old_symbol'))],
            'name'   => ['required', 'max:50'],
            'symbol' => ['required', 'max:10', new UniqueArrayKey($currencies, $this->input('old_code'), $this->input('old_symbol'))],
            'rate'   => ['required', 'numeric', 'gt:0']
        ];
        return $rules;
    }
}
