<?php

namespace Domain\Payment\Contracts;

use Domain\Payment\Models\Transaction;


interface PaymentGatewayInterface
{
    public function processPayment(Transaction $transaction): mixed;

    public function getGatewayName(): string;

    public function validateTransactionData(Transaction $transaction): bool;
}
