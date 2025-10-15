<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Contracts\BaseGateway;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;

class StripeGateway extends BaseGateway
{
    public function validateTransactionData(Transaction $transaction): bool
    {
        return $transaction->amount > 0 && $transaction->user_id !== null;
    }

    public function processPayment(Transaction $transaction): Transaction
    {
        if (!$this->validateTransactionData($transaction)) {
            $transaction->update(['status' => Status::FAILED]);
            return $transaction;
        }

        // Generate reference ID
        $referenceId = $this->generateReferenceId();

        // Simulate Stripe API call
        $success = rand(0, 1) == 1;



        if ($success) {
            $dto = new UpdateTransactionDto(
                id: $transaction->id,
                status: Status::SUCCESS->value,
                reference_id: $referenceId,
                metadata: [
                    'transaction_id' => 'txn_' . uniqid(),
                    'payment_method' => 'Credit Card',
                    'message' => 'Payment processed successfully via Stripe.',
                ],
                gateway_response: [
                    'id' => 'ch_' . uniqid(),
                    'object' => 'charge',
                    'status' => 'succeeded',
                    'paid' => true,
                    'created' => time(),
                ],
            );
        } else {
            $dto = new UpdateTransactionDto(
                id: $transaction->id,
                status: Status::FAILED->value,
                reference_id: $referenceId,
            );
        }

        $action = new UpdateTransactionAction();
        $resource = $action->execute($dto);

        Log::info('Stripe Gateway Response', ['response' => $resource->getData()]);

        return $resource->getData();
    }

    public function getGatewayName(): string
    {
        return Gateway::STRIPE->value;
    }
}
