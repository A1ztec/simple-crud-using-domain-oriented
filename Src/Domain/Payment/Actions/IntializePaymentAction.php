<?php

namespace Domain\Payment\Actions;

use Exception;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Gateways\CodGateway;
use Domain\Payment\Jobs\GatewayPaymentProcess;
use Domain\Payment\Factories\PaymentGatewayFactory;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class IntializePaymentAction
{
    public function execute(CreateTransactionDto $dto): PaymentResourceInterface
    {

        $resource = (new CreateTransactionAction())->execute($dto);

        Log::channel('payment')->info('Transaction creation resource', ['resource' => $resource]);
        if (!$resource->isSuccess()) {
            return $resource;
        }

        $transaction = $resource->getData();

        try {
            $gateway = (new PaymentGatewayFactory())->make($dto->gateway);

            if ($gateway instanceof CodGateway) {
                $processedTransaction = $gateway->processPayment($transaction);
                return new IntializePaymentSuccessResource($processedTransaction, message: 'Cash on Delivery selected. Transaction created successfully.');
            }
            GatewayPaymentProcess::dispatch($gateway, $transaction)->onQueue('payment')->delay(now()->addSeconds(5));
            return new IntializePaymentSuccessResource($transaction, message: 'Payment processing initiated.');
        } catch (Exception $e) {
            Log::channel('payment')->error('Transaction initialization failed', ['error' => $e->getMessage()]);
            return new IntializePaymentFailedResource();
        }
    }
}
