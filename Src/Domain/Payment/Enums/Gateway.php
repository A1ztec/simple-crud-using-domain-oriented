<?php

namespace Domain\Payment\Enums;

enum Gateway: string
{

    case STRIPE = 'stripe';
    case COD = 'cod';
}
