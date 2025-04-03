<?php

namespace App\Http\Requests;

use App\Enums\ContactAttributeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ContactSettingsRequest extends FormRequest
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
        $existingSettings = json_decode(site_settings('contact_meta_data'), true) ?? [];
        $oldAttributeName = $this->input('old_attribute_name');
        $rules = [
            "attribute_name" => [
                "required",
                "regex:/^.*[^0-9].*$/",
                "not_regex:/^\d+$/",
                function ($attribute, $value, $fail) use ($existingSettings, $oldAttributeName) {
                    if (array_key_exists($value, $existingSettings) && $value !== $oldAttributeName) {
                        $fail(translate('Attribute name must be unique.'));
                    }
                },
            ],
            "attribute_type" => [
                "required",
                "in:" . implode(',', ContactAttributeEnum::values([], true))
            ],
        ];

        return $rules;
    }
}
