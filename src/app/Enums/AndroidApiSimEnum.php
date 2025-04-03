<?php
  
namespace App\Enums;
 
enum AndroidApiSimEnum :int {

    use EnumTrait;
    
    case ACTIVE   = 1;
    case INACTIVE = 2;
}