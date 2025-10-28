<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class HandleOrderTransactionSuccessResource implements OrderResourceInterface
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

    public function getData(): array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return 'Order transaction handled successfully.';
    }
}
