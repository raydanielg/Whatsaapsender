<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ScheduleLimitRule;


class StoreSMSRequest extends FormRequest
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
        
        session()->put('old_sms_message',request('message', ''));
        session()->put('number',request('number', ''));
        return [
            'message'       => 'required',
            'sms_type'      => 'required|in:plain,unicode',
            'schedule_date' => ['required_if:schedule,2', new ScheduleLimitRule(request()->schedule_date)],
            'group_id'      => 'nullable|array|min:1',
            'group_id.*'    => 'nullable|exists:groups,id',
        ];
    }
}
