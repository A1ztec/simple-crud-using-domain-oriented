<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class ProcessPendingOrderFailedResource implements OrderResourceInterface
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
        return trans('Failed_to_process_pending_order');
    }

    public function getData(): ?array
    {
        return null;
    }
}
