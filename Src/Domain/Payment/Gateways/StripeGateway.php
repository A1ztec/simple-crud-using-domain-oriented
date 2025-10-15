<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
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
        $referenceId = $this->generateReferenceId();


        $success = rand(0, 1) == 1;

        if ($success) {
            $data =  [
                'user_id' => $transaction->user->id,
                'status' => Status::SUCCESS->value,
                'amount' => $transaction->amount * 100,
                'reference_id' => $referenceId,
                'gateway' => $this->getGatewayName(),
                'meta_data' => [
                    'transaction_id' => 'txn_' . uniqid(),
                    'payment_method' => 'Credit Card',
                    'message' => 'Payment processed successfully via Stripe.',
                ],
                'gateway_response' => [
                    'id' => 'ch_' . uniqid(),
                    'object' => 'charge',
                    'status' => 'succeeded',
                    'paid' => true,
                    'created' => time(),
                ],
            ];
            $transaction = $this->updateTransaction($transaction, $data);
            return $transaction;
        }


        $data =  [
            'user_id' => $transaction->user->id,
            'status' => Status::FAILED->value,
            'amount' => $transaction->amount,
            'reference_id' => $referenceId,
        ];
        $transaction = $this->updateTransaction($transaction, $data);
        return $transaction;
    }

    private function updateTransaction(Transaction $transaction, array $data): Transaction
    {

        $dto = new UpdateTransactionDto(
            id: $transaction->id,
            amount: $data['amount'],
            status: $data['status'],
            reference_id: $data['reference_id'],
            metadata: $data['meta_data'] ?? null,
            gateway_response: $data['gateway_response'] ?? null,
        );

        $action = new UpdateTransactionAction();
        $resource = $action->execute($dto);

        return $resource->getData();
    }

    public function getGatewayName(): string
    {
        return Gateway::STRIPE->value;
    }
}
