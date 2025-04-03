<?php
  
namespace App\Enums;
 
enum TicketStatusEnum :int {

    use EnumTrait;
    
    case RUNNING  = 1;
    case ANSWERED = 2;
    case REPLIED  = 3;
    case CLOSED   = 4;

}