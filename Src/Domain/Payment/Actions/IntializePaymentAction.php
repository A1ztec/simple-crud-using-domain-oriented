<?php

namespace Domain\Payment\Actions;

use Exception;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Factories\PaymentGatewayFactory;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Domain\Payment\Resources\IntializePaymentFailedResource;

class IntializePaymentAction
{
    public function __invoke(CreateTransactionDto $dto): PaymentResourceInterface
    {
        $resource = (new CreateTransactionAction())($dto);

        Log::channel('payment')->info('Transaction creation resource', ['resource' => $resource]);

        if (!$resource->isSuccess()) {
            return $resource;
        }

        $transaction = $resource->getData();

        try {
            $gateway = (new PaymentGatewayFactory())->make($dto->gateway);
            return $gateway->processPayment($transaction);
        } catch (Exception $e) {
            Log::channel('payment')->error('Transaction initialization failed', ['error' => $e->getMessage()]);
            return new IntializePaymentFailedResource();
        }
    }
}
