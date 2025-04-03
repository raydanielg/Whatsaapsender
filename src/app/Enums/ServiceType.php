<?php

namespace App\Enums;

enum ServiceType: int
{
    use EnumTrait;

    case SMS      = 1;
    case WHATSAPP = 2;
    case EMAIL    = 3;
}