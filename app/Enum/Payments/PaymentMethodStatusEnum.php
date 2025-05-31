<?php

namespace App\Enum\Payments;

enum PaymentMethodStatusEnum:string {
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';

}
