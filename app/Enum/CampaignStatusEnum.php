<?php

namespace App\Enum;

enum CampaignStatusEnum: string
{
    case WAITING    = 'WAITING';
    case SENDING    = 'SENDING';
    case SENT       = 'SENT';
    case BLOCKED    = 'BLOCKED';
}
