<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Contracts\BaseGateway;

class CodGateway extends BaseGateway
{
    public function validateTransactionData(Transaction $transaction): bool
    {
        return $transaction->amount > 0;
    }

    public function processPayment(Transaction $transaction): Transaction
    {
        if (!$this->validateTransactionData($transaction)) {
            $transaction->update(['status' => Status::FAILED]);
            return $transaction;
        }


        $transaction->update(['status' => Status::PROCESSING]);


        sleep(5);


        $referenceId = $this->generateReferenceId();


        $dto = new UpdateTransactionDto(
            id: $transaction->id,
            status: Status::SUCCESS->value,
            reference_id: $referenceId,
            metadata: [
                'estimated_delivery' => now()->addDays(5)->toDateString(),
                'payment_method' => 'Cash on Delivery',
                'message' => 'Cash on Delivery order placed successfully. Please prepare the payment upon delivery.',
            ],
            gateway_response: [
                'gateway' => $this->getGatewayName(),
                'processed_at' => now()->toDateTimeString(),
                'reference' => $referenceId,
            ]
        );


        $action = new UpdateTransactionAction();
        $resource = $action->execute($dto);

        return $resource->getData();
    }

    public function getGatewayName(): string
    {
        return Gateway::COD->value;
    }
}
