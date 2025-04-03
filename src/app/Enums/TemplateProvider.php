<?php

namespace App\Enums;

enum TemplateProvider: int
{
    use EnumTrait;

    case SYSTEM    = 0;
    case BEE_FREE  = 1;
    case CK_EDITOR = 2;
}