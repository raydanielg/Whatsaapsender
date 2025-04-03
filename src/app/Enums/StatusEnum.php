<?php
  
namespace App\Enums;
 
enum StatusEnum {

    case TRUE;
    case FALSE;

    /**
     * get enum status
     */
    public function status(): string
    {
        return match($this) 
        {
            StatusEnum::TRUE  => '1',   
            StatusEnum::FALSE => '0',   
        };
    }


    public static function toArray() :array{
        return [
            'Active'   => (StatusEnum::TRUE)->status(),
            'Inactive' => (StatusEnum::FALSE)->status()
        ];
    }

    public static function getBoolean(int $value) {

        return match ($value) {
            
            self::TRUE  => true,
            self::FALSE => false,
        };
    } 

  
   
}