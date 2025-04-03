<?php

namespace App\Http\Requests;

use App\Models\Campaign;
use App\Rules\MessageFileValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
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

            
            'name'           => 'required',
            'channel'        => ['required', Rule::in([Campaign::WHATSAPP,Campaign::SMS, Campaign::EMAIL])],
            'schedule_date'  => 'required|date',
            'repeat_number'  => 'required|numeric',
            'subject'        => 'required_if:channel,email',
            'smsType'        => 'required_if:channel,sms',
            'logic'          => 'required_if:group_logic,true',
            'attribute_name' => 'required_if:group_logic,true',
            'repeat_format'  => 'required|in:year,month,day',
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

        $fileFields = ['document', 'audio', 'image', 'video'];

        foreach ($fileFields as $field) {
            if ($this->hasFile($field)) {
                $rules[$field] = ['required', new MessageFileValidationRule($field)];
                $rules['message'] = [];
                break;
            }
        }

        return $validationRules;
    }
}
