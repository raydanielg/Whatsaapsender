<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappCampaignRequest extends FormRequest
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
        return [
            'contacts' => 'required|array',
            'name' => 'required',
            'method' => ['required', 'in:without_cloud_api,cloud_api'],
            'schedule_at' => ['required', 'date_format:Y-m-d H:i'],
            'repeat_time' => ['required', 'integer', 'gte:0'],
            'repeat_format' => [
                'nullable',
                'required_if:repeat_time,>0'
            ],
            'message.message_body' => 'required_if:method,without_cloud_api',
            'whatsapp_template_id' => 'required_if:method,cloud_api',
            
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
