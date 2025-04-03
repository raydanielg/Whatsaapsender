<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueArrayKey implements Rule
{
    private $array;
    private $message;
    private $old_code;
    private $old_symbol;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $array, $old_code = null, $old_symbol = null, $message = null)
    {
        $this->array          = $array;
        $this->old_code       = $old_code;
        $this->old_symbol     = $old_symbol;
        $this->message        = $message ?: 'The :attribute must be unique.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        
       
        if ($this->old_code == $value || $this->old_symbol == $value) {
            return true;
        }
        foreach ($this->array as $code => $item) {
           
            if ($code == $value || (is_array($item) && in_array($value, $item))) {
                
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
