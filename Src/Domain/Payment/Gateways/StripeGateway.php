<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Contracts\PaymentGatewayInterface;

class StripeGateway  implements PaymentGatewayInterface
{
    public function validateTransactionData(Transaction $transaction): bool
    {
        return $transaction->amount > 0 && $transaction->user_id !== null;
    }

    public function processPayment(Transaction $transaction): Transaction
    {

        Log::channel("payment")->info("status before update: " . $transaction->status->value);
        $transaction->update(['status' => Status::PROCESSING]);

        if (!$this->validateTransactionData($transaction)) {
            $transaction->update(['status' => Status::FAILED]);
            return $transaction;
        }

        Log::channel("payment")->info("status after update: " . $transaction->status->value);


        $success = rand(0, 1) == 1;



        if ($success) {
            $dto = new UpdateTransactionDto(
                id: $transaction->id,
                status: Status::SUCCESS->value,
                reference_id: $transaction->reference_id,
                metadata: [
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
                reference_id: $transaction->reference_id,
                metadata: [
                    'payment_method' => 'Credit Card',
                    'message' => 'Payment failed via Stripe.',
                ],
            );
        }

        $action = new UpdateTransactionAction();
        $resource = $action->execute($dto);

        return $resource->getData();
    }

    public function getGatewayName(): string
    {
        return Gateway::STRIPE->value;
    }
}
