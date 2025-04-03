<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappServerRequest extends FormRequest
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
            'server_host' => [
                'required', 
                'ip'
            ],
            'server_port' => [
                'required', 
                'numeric'
            ],
            'max_retries' => [
                'required', 
                'numeric'
            ],
            'reconnect_interval' => [
                'required', 
                'numeric'
            ],
            
        ];

        return $rules;
    }
}
