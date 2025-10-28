<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class HandleOrderTransactionFailedResource implements OrderResourceInterface
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
        return 'handle order transaction failed.';
    }

    public function getData(): array
    {
        return [];
    }
}
