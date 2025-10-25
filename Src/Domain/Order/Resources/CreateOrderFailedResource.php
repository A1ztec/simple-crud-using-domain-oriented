<?php


namespace Domain\Order\Resources;


use Domain\Order\Resources\Contracts\OrderResourceInterface;


class CreateOrderFailedResource implements OrderResourceInterface
{
    public function __construct(private ?string $message = null) {}

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
        return $this->message ?? 'Order creation failed';
    }

    public function getData(): null
    {
        return null;
    }
}
