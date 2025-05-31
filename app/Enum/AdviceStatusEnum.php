<?php

namespace App\Enum;

enum AdviceStatusEnum: string
{
    case SHOW_IN_HOME = 'SHOW_IN_HOME';
    case SHOW_IN_LIST = 'SHOW_IN_LIST';

    case DRAFT = 'DRAFT';

    case REJECTED = 'REJECTED';
}
