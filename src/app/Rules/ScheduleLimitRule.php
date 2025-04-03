<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ScheduleLimitRule implements Rule
{
    public $schedule_time;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($schedule_date)
    {
        $this->schedule_time = $schedule_date;
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
        
        if($value && Carbon::parse($value)->diffInSeconds() <= 120) {

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
        return "You cannot schedule within the next 2 minutes.";
    }
}
