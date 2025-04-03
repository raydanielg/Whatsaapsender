<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ScheduleLimitRule;

class StoreWhatsAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $requestData = $this->all();
        
        $placeholderFields = [];
        $validationRules = [];
        $validationRules += [
            'schedule_date'      => ['required_if:schedule,2', new ScheduleLimitRule($this->input('schedule_date'))],
            'group_id'           => 'nullable|array|min:1',
            'group_id.*'         => 'nullable|exists:groups,id',
            'whatsapp_device_id' => 'required'
        ];
        if(request()->input("cloud_api") == "true") {
            
            foreach ($requestData as $key => $value) {

                if (strpos($key, '_placeholder_') !== false) {
                    $placeholderFields[$key] = $value;
                }
                if (strpos($key, '_header_media') !== false) {
                    $placeholderFields[$key] = $value;
                }
                if (strpos($key, '_button_') !== false) {
                    $placeholderFields[$key] = $value;
                }
            }
            foreach ($placeholderFields as $key => $value) {
                $validationRules[$key] = 'required';
            }
        } 
        if(request()->input("without_cloud_api") == "true") {
     
            $validationRules['message'] = 'required';
    
        }

       
        return $validationRules;
    }
}
