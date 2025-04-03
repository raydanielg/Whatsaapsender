<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    use EnumTrait;

    case PENDING    = 1;
    case SUCCESS    = 2;
    case FAILED     = 3;
    case PROCESSING = 4;
    case CANCEL     = 5;
}