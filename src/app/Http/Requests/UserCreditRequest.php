<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UserCreditRequest extends FormRequest
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
        return [
            'type'            => ['required', 'in:' . StatusEnum::TRUE->status() . ',' . StatusEnum::FALSE->status()],
            'sms_credit'      => 'nullable|integer|gt:0',
            'email_credit'    => 'nullable|integer|gt:0',
            'whatsapp_credit' => 'nullable|integer|gt:0',
        ];
    }
}
