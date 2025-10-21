<?php


namespace Domain\Payment\Resources;

use Domain\Payment\Resources\Contracts\PaymentResourceInterface;


class IntializePaymentFailedResource implements PaymentResourceInterface
{

    public function __construct(private ?string $message = null) {}


    public function isSuccess(): bool
    {
        return false;
    }

    public function getCode(): int
    {
        return 500;
    }

    public function getMessage(): string
    {
        return $this->message ?? 'Payment initialization failed';
    }

    public function getData(): null
    {
        return null;
    }
}
