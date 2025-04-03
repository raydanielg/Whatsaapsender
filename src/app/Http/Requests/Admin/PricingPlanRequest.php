<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PricingPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // You can add any authorization logic here
    }

    /**
     * Prepare the data for validation.
     * Convert string "true"/"false" to boolean values.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'allow_carry_forward'       => $this->convertToBoolean($this->allow_carry_forward),
            'allow_user_android'        => $this->convertToBoolean($this->allow_user_android),
            'sms_multi_gateway'         => $this->convertToBoolean($this->sms_multi_gateway),
            'allow_user_whatsapp'       => $this->convertToBoolean($this->allow_user_whatsapp),
            'mail_multi_gateway'        => $this->convertToBoolean($this->mail_multi_gateway),
            'allow_admin_whatsapp'      => $this->convertToBoolean($this->allow_admin_whatsapp),
            'allow_admin_creds'         => $this->convertToBoolean($this->allow_admin_creds),
        ]);
    }

    /**
     * Helper function to convert "true"/"false" strings to boolean.
     */
    private function convertToBoolean($value)
    {
        if ($value === 'on') {
            return true;
        }

        if ($value === null) {
            return false;
        }

        return $value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                => 'required|max:255',
            'description'         => 'nullable',
            'amount'              => 'required|numeric|min:0',
            'allow_carry_forward' => 'nullable',
            'duration'            => 'required|integer',
        ];

        if ($this->input('allow_admin_creds')) {
            $rules['whatsapp_device_limit']        = ['requiredIf:allow_admin_whatsapp,true', 'nullable', 'numeric', 'min:-1'];
            $rules['sms_credit_admin']             = ['numeric', 'min:-1'];
            $rules['sms_credit_per_day_admin']     = ['numeric', 'min:0'];
            $rules['whatsapp_credit_admin']        = ['numeric', 'min:-1'];
            $rules['whatsapp_credit_per_day_admin'] = ['numeric', 'min:0'];
            $rules['email_credit_admin']           = ['numeric', 'min:-1'];
            $rules['email_credit_per_day_admin']   = ['numeric', 'min:0'];
        } else {
            $rules = array_merge($rules, [
                'user_android_gateway_limit'    => ['requiredIf:allow_user_android,true', 'nullable', 'numeric', 'min:-1'],
                'user_whatsapp_device_limit'    => ['requiredIf:allow_user_whatsapp,true', 'nullable', 'numeric', 'min:-1'],
                'mail_gateways'                 => ['requiredIf:mail_multi_gateway,true'],
                'total_mail_gateway'            => ['requiredIf:mail_multi_gateway,true|array'],
                'total_mail_gateway.*'          => ['numeric', 'min:0'],
                'sms_gateways'                  => ['requiredIf:sms_multi_gateway,true'],
                'total_sms_gateway'             => ['requiredIf:sms_multi_gateway,true|array'],
                'total_sms_gateway.*'           => ['numeric', 'min:0'],
                'sms_credit_user'               => ['numeric', 'min:-1'],
                'sms_credit_per_day_user'       => ['numeric', 'min:0'],
                'whatsapp_credit_user'          => ['numeric', 'min:-1'],
                'whatsapp_credit_per_day_user'  => ['numeric', 'min:0'],
                'email_credit_user'             => ['numeric', 'min:-1'],
                'email_credit_per_day_user'     => ['numeric', 'min:0'],
            ]);
        }

        return $rules;
    }
}
