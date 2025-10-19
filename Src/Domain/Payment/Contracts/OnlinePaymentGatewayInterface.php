<?php


namespace Domain\Payment\Contracts;

use Domain\Payment\Models\Transaction;

interface OnlinePaymentGatewayInterface
{

    public function callBack(): array;

    public function formatData(Transaction $transaction): array;
}
