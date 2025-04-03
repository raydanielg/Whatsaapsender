<?php
  
namespace App\Enums;
 
enum ContactAttributeEnum :int {

    use EnumTrait;
    
    case DATE    = 1;
    case BOOLEAN = 2;
    case NUMBER  = 3;
    case TEXT    = 4;
}