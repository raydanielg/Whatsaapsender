<?php

namespace App\Http\Requests;

use App\Enums\AndroidApiSimEnum;
use App\Enums\StatusEnum;
use App\Models\AndroidApiSimInfo;
use Illuminate\Foundation\Http\FormRequest;

class SmsCampaignRequest extends FormRequest
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
        if(!$this->gateway_id) {
            $user = auth()->user();
            $this->merge([
                'gateway_id' => $this->getGatewayID($user),
            ]);
        }
    }
    private function getGatewayID($user) {

        $gateway_id = "-1";
        $method = $this->getDispatchMethod($user);
        $this->merge([
            
            'method' => $method,
        ]);
        if($method == StatusEnum::FALSE->status()) {

            $availble_sms_android_gateways = json_decode(site_settings('accessible_sms_android_gateways'), true);
            if($user 
                && $user?->gateway_credentials 
                && isset($user->gateway_credentials->specific_gateway_access, $user->gateway_credentials->accessible_sms_android_gateways) 
                && $user->gateway_credentials->specific_gateway_access == StatusEnum::TRUE->status()) {
                
                $availble_sms_android_gateways = $user->gateway_credentials->accessible_sms_android_gateways;
            } 
            $android_id   = array_rand(array_flip($availble_sms_android_gateways));
            $gateway_info = AndroidApiSimInfo::where('android_gateway_id', $android_id)
                ->where('status', AndroidApiSimEnum::ACTIVE->value)
                ->get();
            if ($gateway_info->isNotEmpty()) {
                $gateway_id = $gateway_info->random()->id;
            }
        } else {

            $availble_sms_api_gateways = json_decode(site_settings('accessible_sms_api_gateways'), true);
            if($user 
                && $user?->gateway_credentials 
                && isset($user->gateway_credentials->accessible_sms_api_gateways, $user->gateway_credentials->accessible_sms_api_gateways) 
                && $user->gateway_credentials->accessible_sms_api_gateways == StatusEnum::TRUE->status()) {
                
                $availble_sms_api_gateways = $user->gateway_credentials->accessible_sms_api_gateways;
            } 
            $gateway_id = array_rand(array_flip($availble_sms_api_gateways));
        }
        return (string)$gateway_id;
    }

    private function getDispatchMethod($user) {

        $method = site_settings('in_application_sms_method');
        if($user && $user?->gateway_credentials && isset($user->gateway_credentials->specific_gateway_access) && $user->gateway_credentials->specific_gateway_access == StatusEnum::TRUE->status()) {

            $method = $user->gateway_credentials->in_application_sms_method;
        } 
        return $method;
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
            'method'        => ['required', 'in:' . StatusEnum::TRUE->status() . ',' . StatusEnum::FALSE->status()],
            'schedule_at'   => ['required', 'date_format:Y-m-d H:i'],
            'repeat_time'   => ['required', 'integer', 'gte:0'],
            'repeat_format' => [
                'nullable',
                'required_if:repeat_time,>0'
            ],
            'sms_type'             => ['required', 'in:plain,unicode'],
            'message'              => 'array',
            'message.message_body' => 'required',
            'gateway_id'           => 'required'
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
