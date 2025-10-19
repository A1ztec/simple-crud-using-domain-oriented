
<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Resources\UpdateTransactionFailedResource;
use Domain\Payment\Resources\UpdateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class CodGateway implements PaymentGatewayInterface
{
    public function validateTransactionData(Transaction $transaction): bool
    {
        return $transaction->amount > 0;
    }

    public function processPayment(Transaction $transaction): PaymentResourceInterface
    {
        if (!$this->validateTransactionData($transaction)) {
            $transaction->update(['status' => Status::FAILED]);
            return new UpdateTransactionFailedResource();
        }

        $transaction->update(['status' => Status::PROCESSING]);

        $dto = new UpdateTransactionDto(
            id: $transaction->id,
            status: Status::SUCCESS->value,
            reference_id: $transaction->reference_id,
            metadata: [
                'estimated_delivery' => now()->addDays(5)->toDateString(),
                'payment_method' => 'Cash on Delivery',
                'message' => 'Cash on Delivery order placed successfully , Please prepare the payment upon delivery',
            ],
            gateway_response: [
                'gateway' => $this->getGatewayName(),
                'processed_at' => now()->toDateTimeString(),
                'reference' => $transaction->reference_id,
            ]
        );

        return (new UpdateTransactionAction())->execute($dto);
    }

    public function getGatewayName(): string
    {
        return Gateway::COD->value;
    }
}
