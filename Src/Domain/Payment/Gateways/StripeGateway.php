<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Contracts\BaseGateway;

class StripeGateway extends BaseGateway
{
    public function validateTransactionData(Transaction $transaction): bool
    {
        return $transaction->amount > 0 && $transaction->user_id !== null;
    }

    public function processPayment(Transaction $transaction): array
    {
        $referenceId = $this->generateReferenceId();

        // Simulate Stripe API call with random success/failure
        $success = rand(0, 1) == 1;

        if ($success) {
            return [
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
        }

        // Simulate failure
        return [
            'user_id' => $transaction->user->id,
            'status' => Status::FAILED->value,
            'amount' => $transaction->amount,
            'reference_id' => $referenceId,
        ];
    }

    public function getGatewayName(): string
    {
        return Gateway::STRIPE->value;
    }
}
