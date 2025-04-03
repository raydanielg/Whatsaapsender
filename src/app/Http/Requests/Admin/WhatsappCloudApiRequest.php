<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappCloudApiRequest extends FormRequest
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
            "name" => "required",
        ];
        $required_credentials = config("setting.whatsapp_business_credentials.required");
        
        foreach($required_credentials as $required_cred_key => $required_cred_value) {
            
            $rules['credentials.' . $required_cred_key] = 'required';
        }
        return $rules;
    }
}
