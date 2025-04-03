<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExtensionCheckRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $extension;
    public function __construct(array $extension =  ['csv','xlsx','xls','xlsb','xlsm'])
    {
        $this->extension =  $extension;
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

        if(in_array($value->getClientoriginalextension(),$this->extension)){
            return true;
        } else{
            return false;
        }
       
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return translate('The file format is invalid!! ');
    }
}
