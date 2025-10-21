<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\StatusEnum;
use Domain\Payment\Enums\GatewayEnum;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Models\CodPaymentTransaction;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class CodGateway implements PaymentGatewayInterface
{
    public function processPayment(Transaction $transaction): PaymentResourceInterface
    {

        $codTransaction =  CodPaymentTransaction::create([
            'payment_id' => $transaction->id,
            'amount' => $transaction->amount,
            'status' => StatusEnum::SUCCESS,
        ]);


        $dto = new UpdateTransactionDto(
            status: StatusEnum::SUCCESS,
            payment_method_gateway_id: $codTransaction->id,
            payment_method_gateway_type: CodPaymentTransaction::class
        );

        return (new UpdateTransactionAction())($dto, $transaction);
    }

    public function getGatewayName(): string
    {
        return GatewayEnum::COD;
    }
}
