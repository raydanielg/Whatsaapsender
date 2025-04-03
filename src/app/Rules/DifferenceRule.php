<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DifferenceRule implements Rule
{
    public $minimum_delay;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min_delay)
    {
        $this->minimum_delay = $min_delay;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        
        if($value - $this->minimum_delay < 10){
      
            return false;
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
        return 'The difference between Minimum Delay and Maximum Delay needs to be 10';
    }
}
