<?php

namespace Domain\Payment\Contracts;

use Domain\Payment\Models\Transaction;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;


interface PaymentGatewayInterface
{
    public function processPayment(Transaction $transaction): PaymentResourceInterface;

    public function getGatewayName(): string;

    public function validateTransactionData(Transaction $transaction): bool;
}
