<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileExtentionCheckRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $extention;
    public $type;
    public $message;
    public $counter;
    public function __construct(mixed $extention,string $type = 'image' ,int $counter = 0)
    {
        
       $this->extention = $extention;
       $this->type      = $type;
       $this->counter   = $counter;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        
        $flag = 1;
        if(is_array($value)){
            
            if(count($value) + $this->counter > (int) site_settings('max_file_upload')) {

                $this->message = " ".translate("You Can Not Upload More Than ").site_settings('max_file_upload').translate(' File At a Time');
                $flag = 0;
            } else {

                foreach($value as $file) {

                    $flag = $this->checkRule($file);
                    if($flag == 0){
                        break;
                    }
                }
            }

        }
        else{

            $flag = $this->checkRule($value);
        }

        if( $flag == 1){
            return true;
        }
        return false;

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


    public function checkRule(mixed $file) : int{
        
        $fileSizeInBytes = $file->getSize();
        $indicator = 1;
      
        if( round($fileSizeInBytes / 1024) >  (int) site_settings('max_file_size')){
            $this->message = translate($this->type.' Size Must be Under '). site_settings('max_file_size'). translate('KB');
            $indicator = 0;
        } elseif (!in_array($file->getClientOriginalExtension(), $this->extention)) {
            
            $this->message = translate($this->type.' Must be '.implode(", ", $this->extention).' Format');
            $indicator = 0;
        }
        return  $indicator;
    }
}
