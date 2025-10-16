<?php


namespace Domain\Payment\Resources;

use Domain\Payment\Resources\Contracts\PaymentResourceInterface;


class CreateTransactionFailedResource implements PaymentResourceInterface
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
        return $this->message ?? 'Failed to create transaction.';
    }

    public function getData(): null
    {
        return null;
    }
}
