<?php

namespace App\Enums;

enum SubscriptionStatus: int
{
    use EnumTrait;

    case RUNNING   = 1;
    case EXPIRED   = 2;
    case REQUESTED = 3;
    case INACTIVE  = 4;
    case RENEWED   = 5;
}