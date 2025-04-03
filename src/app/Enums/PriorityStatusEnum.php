<?php

namespace App\Enums;

enum PriorityStatusEnum: int
{
    use EnumTrait;

    case LOW    = 1;
    case MEDIUM = 2;
    case HIGH   = 3;
}