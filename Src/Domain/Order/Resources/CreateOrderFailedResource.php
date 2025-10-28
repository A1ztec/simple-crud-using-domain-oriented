<?php


namespace Domain\Order\Resources;


use Domain\Order\Resources\Contracts\OrderResourceInterface;


class CreateOrderFailedResource implements OrderResourceInterface
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
        return  trans('Order_creation_failed');
    }

    public function getData(): null
    {
        return null;
    }
}
