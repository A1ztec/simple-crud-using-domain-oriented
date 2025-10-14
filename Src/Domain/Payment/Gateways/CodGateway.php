<?php


namespace Domain\Payment\Gateways;


use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Support\Facades\Auth;
use Domain\Payment\Contracts\BaseGateway;


class CodGateway extends BaseGateway
{

    public function validateTransactionData(array $data): bool
    {
        return isset($data['amount']) && $data['amount'] > 0;
    }

    public function processPayment(array $data): array
    {
        
        $referenceId = $this->generateReferenceId();

        // toDo: Logic for Cash on Delivery payment processing

        return [
            'user_id' => Auth::id(),
            'status' => Status::SUCCESS->value,
            'amount' => $data['amount'],
            'reference_id' => $referenceId,
            'gateway' => $this->getGatewayName(),
            'meta_data' => [
                'estimated_delivery' => now()->addDays(5)->toDateString(),
                'payment_method' => 'Cash on Delivery',
                'message' => 'Cash on Delivery order placed successfully. Please prepare the payment upon delivery.',
            ],
        ];
    }


    public function getGatewayName(): string
    {
        return Gateway::COD->value;
    }
}
