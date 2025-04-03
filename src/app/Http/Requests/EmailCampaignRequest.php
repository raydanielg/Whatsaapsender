<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class EmailCampaignRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        if(!$this->has('gateway_id')) {
            $user = auth()->user();
            $this->merge([
                'gateway_id' => $this->getGatewayID($user),
            ]);
        }
    }
    private function getGatewayID($user) {

        $availble_email_gateways = json_decode(site_settings('accessible_email_gateways'), true);
        if($user 
            && $user?->gateway_credentials && isset($user->gateway_credentials->specific_gateway_access, $user->gateway_credentials->accessible_email_gateways) 
            && $user->gateway_credentials->specific_gateway_access == StatusEnum::TRUE->status()) {
        
            $availble_email_gateways = $user->gateway_credentials->accessible_email_gateways;
        } 
        $gateway_id = array_rand(array_flip($availble_email_gateways));
        return (string)$gateway_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contacts'      => 'required|array',
            'name'          => 'required',
            'schedule_at'   => ['required', 'date_format:Y-m-d H:i'],
            'repeat_time'   => ['required', 'integer', 'gte:0'],
            'repeat_format' => [
                'nullable',
                'required_if:repeat_time,>0'
            ],
            'message'              => 'array',
            'message.subject'      => 'required',
            'message.message_body' => 'required',
            'gateway_id'           => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ((int)$this->input('repeat_time') > 0 && empty($this->input('repeat_format'))) {
                $validator->errors()->add('repeat_format', 'The repeat format field is required when repeat time is greater than 0.');
            }
        });
    }
}
