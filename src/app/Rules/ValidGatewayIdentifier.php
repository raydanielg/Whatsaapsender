<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Gateway; 

class ValidGatewayIdentifier implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the value is -1 or 0
        if ($value == '-1' || $value == '0') {
            return true;
        }

        // Check if the value exists in the gateways table
        return Gateway::where('uid', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not valid.';
    }
}
