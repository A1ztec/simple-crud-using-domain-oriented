<?php

namespace Domain\Payment\Resources;

use Domain\Payment\Models\Transaction;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class UpdateTransactionSuccessResource implements PaymentResourceInterface
{

    public function __construct(private Transaction $transaction) {}
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
        return 'Payment updated successfully';
    }

    public function getData(): Transaction|null
    {
        return $this->transaction;
    }
}
