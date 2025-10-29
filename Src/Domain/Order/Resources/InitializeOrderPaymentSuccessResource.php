<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class InitializeOrderPaymentSuccessResource implements OrderResourceInterface
{
    public function __construct(
        public  array $data,
    ) {}

    public function isSuccess(): bool
    {
        return true;
    }

    public function getCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return trans('Initialize_order_payment_successfully');
    }
    public function getData(): array
    {
        return $this->data;
    }
}
