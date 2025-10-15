<?php


namespace Domain\Payment\Resources;

use Domain\Payment\Resources\Contracts\PaymentResourceInterface;


class IntializePaymentFailedResource implements PaymentResourceInterface

{


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
        return 'Payment initialization failed';
    }

    public function getData(): null
    {
        return null;
    }
}
