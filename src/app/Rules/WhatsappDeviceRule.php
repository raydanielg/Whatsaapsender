<?php

namespace App\Rules;

use App\Models\WhatsappDevice;
use Illuminate\Contracts\Validation\Rule;

class WhatsappDeviceRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $id;
    public function __construct($id)
    {
        $this->id = $id;
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
        $whatsapp = WhatsappDevice::where('id','!=', $this->id)->pluck('number')->toArray();
        if(in_array($value, $whatsapp)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Number is already taken';
    }
}
