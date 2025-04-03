<?php

namespace App\Http\Requests\Admin;

use App\Enums\AndroidApiSimEnum;
use App\Enums\StatusEnum;
use App\Models\AndroidApiSimInfo;
use Illuminate\Foundation\Http\FormRequest;

class CommunicationRequest extends FormRequest
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
        if (isset($this->gateway_id) && $this->gateway_id !== "-2") {
            
            $this->request->remove('custom_gateway_parameter');
        }
        if(!isset($this->gateway_id)) {
            $user = auth()->user();
            $this->merge([
                'gateway_id' => $this->getGatewayID($user),
            ]);
        }
    }

    private function getGatewayID($user) {

        $gateway_id = "-1";
        if(request()->type == "email") {
        
            $availble_email_gateways = json_decode(site_settings('accessible_email_gateways'), true);
            if($user 
                && $user?->gateway_credentials && isset($user->gateway_credentials->specific_gateway_access, $user->gateway_credentials->accessible_email_gateways) 
                && $user->gateway_credentials->specific_gateway_access == StatusEnum::TRUE->status()) {
            
                $availble_email_gateways = $user->gateway_credentials->accessible_email_gateways;
            } 
            $gateway_id = $availble_email_gateways ? array_rand(array_flip($availble_email_gateways)) : $gateway_id;
        }
        if(request()->type == "sms") {

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
                $android_id = array_rand(array_flip($availble_sms_android_gateways));
                $gateway_info = AndroidApiSimInfo::where('android_gateway_id', $android_id)->where('status', AndroidApiSimEnum::ACTIVE->value)->get();
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

                $gateway_id = $availble_sms_api_gateways ? array_rand(array_flip($availble_sms_api_gateways)) : null;
            }
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
        $rules = [
            'schedule_at' => 'nullable|date_format:Y-m-d H:i',
            'gateway_id'  => 'required',
            'contacts'    => 'required',
        ];

        if(request()->type == 'sms') {

            $rules['message.message_body'] = ['required'];
        }
        if(request()->type == 'email') {
            
            $rules['message.subject']      = ['required'];
            $rules['message.message_body'] = ['required'];
            if (request()->gateway_id == -2) {
                $rules['custom_gateway_parameter'] = ['required', 'array'];
            }
        }
        
        if(request()->type == 'whatsapp') {

            $rules['message.message_body'] = ['required_if:method,without_cloud_api'];
            $rules['whatsapp_template_id'] = ['required_if:method,cloud_api'];
        }
        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'message.required'                  => translate('The message data is required.'),
            'gateway_id.required'               => translate('There are no gateway assigned from the Admin'),
            'schedule_at.date_format'           => translate('The :attribute must be in the format "YYYY-MM-DD HH:MM".'),
            'meta_data.contact_number.required' => translate('The recipient contact details are required'),
        ];
        if(request()->type == 'sms') {

            $messages['message.message_body.required'] = translate('Message body is required.');
        }
        if(request()->type == 'email') {

            $messages['message.subject.required']      = translate('Email subject is required.');
            $messages['message.message_body.required'] = translate('The Email body is required');
        }
        
        return $messages;
    }
}
