<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiStoreEmailRequest extends FormRequest
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
            'contact' => 'required|array|min:1',
            'contact.*.subject' => 'required|max:255',
            'contact.*.email' => 'required|email|max:255',
            'contact.*.message' => 'required',
            'contact.*.sender_name' => 'required|max:255',
            'contact.*.reply_to_email' => 'required|email|max:255',
        ];
    }
}
