<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class InitializeOrderPaymentFailedResource implements OrderResourceInterface
{

    public function isSuccess(): bool
    {
        return false;
    }

    public function getCode(): int
    {
        return 400;
    }
    public function getMessage(): string
    {
        return trans('Failed_to_initialize_order_payment');
    }

    public function getData(): array
    {
        return [];
    }
}
