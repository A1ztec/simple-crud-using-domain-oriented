<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;



class ValidateOrderCreationFailedResource implements OrderResourceInterface
{
    public function __construct(private ?string $message = null) {}

    public function isSuccess(): bool
    {
        return false;
    }

    public function getCode(): int
    {
        return 422;
    }

    public function getMessage(): string
    {
        return $this->message ?? 'order_validation_failed';
    }

    public function getData(): null
    {
        return null;
    }
}
