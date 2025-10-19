<?php

namespace Domain\Payment\Resources;

use Domain\Payment\Models\Transaction;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class IntializePaymentSuccessResource implements PaymentResourceInterface
{
    public function __construct(
        private ?array $data = null,
        private ?string $message = null
    ) {}

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
        return $this->message ?? 'Payment initialized successfully';
    }

    public function getData(): array
    {
        return $this->data;
    }
}
