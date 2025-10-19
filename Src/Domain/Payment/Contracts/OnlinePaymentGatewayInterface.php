<?php


namespace Domain\Payment\Contracts;

use Faker\Provider\ar_EG\Payment;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

interface OnlinePaymentGatewayInterface
{

    public function callBack(array $payload): PaymentResourceInterface;

    public function formatData(Transaction $transaction): array;
}
