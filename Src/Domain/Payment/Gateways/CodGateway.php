<?php


namespace Domain\Payment\Gateways;

use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Support\Facades\Auth;
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

        // Simulate Cash on Delivery processing delay

        $transaction->update(['status' => Status::PROCESSING]);

        sleep(5);

        $referenceId = $this->generateReferenceId();

        // toDo: Logic for Cash on Delivery payment processing ...finished

        $data = [
            'user_id' => Auth::id(),
            'status' => Status::SUCCESS->value,
            'amount' => $transaction->amount,
            'reference_id' => $referenceId,
            'gateway' => $this->getGatewayName(),
            'meta_data' => [
                'estimated_delivery' => now()->addDays(5)->toDateString(),
                'payment_method' => 'Cash on Delivery',
                'message' => 'Cash on Delivery order placed successfully. Please prepare the payment upon delivery.',
            ],
        ];

        $transaction =  $this->updateTransaction($transaction, $data);

        return $transaction;
    }

    private function updateTransaction(Transaction $transaction, array $data): Transaction
    {

        $dto = new UpdateTransactionDto(
            id: $transaction->id,
            amount: $data['amount'],
            status: $data['status'],
            reference_id: $data['reference_id'],
            metadata: $data['meta_data'],
            gateway_response: $data['gateway_response'] ?? null
        );
        $action = (new UpdateTransactionAction)->execute($dto);
        return $action->getData();
    }


    public function getGatewayName(): string
    {
        return Gateway::COD->value;
    }
}
