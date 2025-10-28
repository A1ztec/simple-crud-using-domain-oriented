<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class CheckForPendingOrderFailedResource implements OrderResourceInterface
{
    public function isSuccess(): bool
    {
        return false;
    }

    public function getCode(): int
    {
        return 404;
    }

    public function getMessage(): string
    {
        return 'No_Pending_Order_Found';
    }

    public function getData(): ?array
    {
        return null;
    }
}
