<?php


namespace Domain\Order\Enums;

class OrderStatusEnum
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';

    const EXPIRED = 'expired';

    const FAILED = 'failed';

    const PAID_BUT_OUT_OF_STOCK = 'paid_but_out_of_stock';
}
