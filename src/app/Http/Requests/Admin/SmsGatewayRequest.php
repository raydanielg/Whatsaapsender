<?php

namespace App\Http\Requests\Admin;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class SmsGatewayRequest extends FormRequest
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
            'type' => [
                'required',
            ],
            'driver_information' => [
                'required', 
            ],
            'name' => [
                'required', 
            ]
        ];

        if(request()->routeIs('admin.gateway.sms.api.update')) {

            $rules['id'] = ["required",'exists:gateways,id'];
        }
        return $rules;
    }
}
