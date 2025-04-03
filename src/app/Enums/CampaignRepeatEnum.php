<?php

namespace App\Enums;

enum CampaignRepeatEnum: int
{
    use EnumTrait;

    case DAY   = 1;
    case WEEK  = 2;
    case MONTH = 3;
    case YEAR  = 4;
}