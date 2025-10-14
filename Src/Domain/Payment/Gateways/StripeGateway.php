<?php


namespace Domain\Gateways\Gateways;

use Illuminate\Auth\Access\Gate;
use Domain\Payment\Enums\Gateway;
use Domain\Payment\Contracts\BaseGateway;
use Domain\Payment\Enums\Status;

class StripeGateway extends BaseGateway
{

    public function validateTransactionData(array $data): bool
    {
        return isset($data['amount']) && $data['amount'] > 0;
    }
    public function processPayment(array $data): array
    {

        $referenceId = $this->generateReferenceId();
        $success = rand(0, 1) == 1;
        if ($success) {
            return [
                'status' => Status::SUCCESS->value,
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'reference_id' => $referenceId,
                'gateway' => $this->getGatewayName(),
                'meta_data' => [
                    'transaction_id' => 'txn_' . uniqid(),
                    'payment_method' => 'Credit Card',
                    'message' => 'Payment processed successfully via Stripe.',
                ],
                'gateway_response' => [
                    'id' => 'ch_' . uniqid(),
                    'status' => 'succeeded',
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                ],
            ];
        };


        return [
            'status' => Status::FAILED->value,
            'amount' => $data['amount'],
            'reference_id' => $referenceId,
            'gateway' => $this->getGatewayName(),
            'meta_data' => [
                'estimated_delivery' => now()->addDays(5)->toDateString(),
                'error_code' => 'card_declined',
                'error_message' => 'The card was declined.',
            ],
            'gateway_response' => [
                'object' => 'payment_intent',
                'amount' => $data['amount'],
                'amount_capturable' => 0,
                'amount_details' => [
                    'tip' => []
                ],
            ],
        ];
    }

    public function getGatewayName(): string
    {
        return Gateway::STRIPE->value;
    }
}
