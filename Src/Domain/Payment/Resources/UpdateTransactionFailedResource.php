<?php


namespace Domain\Payment\Resources;

use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class UpdateTransactionFailedResource implements PaymentResourceInterface
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
        return 'Payment update failed';
    }

    public function getData(): null
    {
        return null;
    }
}
