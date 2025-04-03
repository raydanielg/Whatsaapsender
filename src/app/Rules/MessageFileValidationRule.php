<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MessageFileValidationRule implements Rule
{

    private $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        if($this->type == 'document'){
            $fileCheck = ['pdf'];
        } else if($this->type == 'audio'){
            $fileCheck = ['mp3', 'aac', 'wav', 'flac'];
        } else if($this->type == 'image'){
            $fileCheck = ['jpg', 'jpeg', 'png', 'webp'];
        } else if($this->type == 'video'){
            $fileCheck = ['mp4', 'mkv', 'mpeg'];
        }
         
        if(in_array($value->getClientoriginalextension(), $fileCheck)){
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
        return 'Give valid file';
    }
}
