<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class EmailGatewayRequest extends FormRequest
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
                'in:smtp,sendgrid,aws,mailjet,mailgun'
            ],
            'driver_information' => [
                'required', 
            ],
            'address' => [
                'required', 
            ],
            'name' => [
                'required', 
            ]
        ];

        if(request()->routeIs('admin.gateway.email.update')) {

            $rules['id'] = ["required",'exists:gateways,id'];
        }
        return $rules;
    }
}
