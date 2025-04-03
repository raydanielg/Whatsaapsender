<?php

namespace App\Enums;

enum CampaignStatusEnum: int
{
    use EnumTrait;

    case CANCEL     = 0;
    case ACTIVE     = 1;
    case DEACTIVE   = 2;
    case COMPLETED  = 3;
    case ONGOING    = 4;
}