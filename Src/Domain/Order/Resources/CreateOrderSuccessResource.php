<?php


namespace Domain\Order\Resources;


use Domain\Order\Resources\Contracts\OrderResourceInterface;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;


class CreateOrderSuccessResource implements OrderResourceInterface
{
    public function __construct(private array $data) {}

    public function isSuccess(): bool
    {
        return true;
    }

    public function getCode(): int
    {
        return 201;
    }

    public function getMessage(): string
    {
        return 'Order created successfully';
    }

    public function getData(): array
    {
        return $this->data;
    }
}
