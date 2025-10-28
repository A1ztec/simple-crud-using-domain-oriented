<?php


namespace Domain\Order\Resources;


use Domain\Order\Resources\Contracts\OrderResourceInterface;


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
        return trans('Order_created_successfully');
    }

    public function getData(): array
    {
        return $this->data;
    }
}
