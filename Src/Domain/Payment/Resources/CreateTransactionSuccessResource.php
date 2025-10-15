<?php


namespace Domain\Payment\Resources;

use Domain\Payment\Models\Transaction;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;


class CreateTransactionSuccessResource implements PaymentResourceInterface
{
    public function __construct(private Transaction $transaction) {}

    public function isSuccess(): bool
    {
        return true;
    }

    public function getCode(): int
    {
        return 201;
    }

    public function getMessage(): string
    {
        return 'Payment created successfully';
    }

    public function getData(): Transaction|null
    {
        return $this->transaction;
    }
}
