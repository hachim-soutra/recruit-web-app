<?php

namespace App\Enum\Payments;

enum PlanTypeStatusEnum: string
{
    case SITE = 'SITE';
    case FREE = 'FREE';
    case SALE = 'SALE';
    case OLD = '';
}
