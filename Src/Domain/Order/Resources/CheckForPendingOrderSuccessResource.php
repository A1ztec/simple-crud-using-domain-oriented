<?php

namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class CheckForPendingOrderSuccessResource implements OrderResourceInterface
{
    public function __construct(private ?array $data = null) {}
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
        return trans('Pending_Order_Found_Successfully');
    }

    public function getData(): ?array
    {
        return $this->data;
    }
}
