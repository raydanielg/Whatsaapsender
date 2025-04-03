<?php
  
namespace App\Enums;
 
enum CommunicationStatusEnum :string {

    use EnumTrait;
    
    case CANCEL     = '0';
    case PENDING    = '1';
    case SCHEDULE   = '2';
    case FAIL       = '3';
    case DELIVERED  = '4';
    case PROCESSING = '5';
}