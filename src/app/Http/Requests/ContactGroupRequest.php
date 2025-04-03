<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class ContactGroupRequest extends FormRequest
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
    public function rules(): array
    {
        $rules = [
            "name" => ["required", "string", "max:191"],
        ];
        return $rules;
    }
}
