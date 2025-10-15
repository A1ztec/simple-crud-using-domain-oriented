<?php

namespace Domain\Payment\Actions;

use Exception;
use Domain\Payment\Gateways\CodGateway;
use Domain\Payment\Factories\PaymentGatewayFactory;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Domain\Payment\Jobs\GatewayPaymentProcess;

class IntializePaymentAction
{
    public function execute(CreateTransactionDto $dto): PaymentResourceInterface
    {

        $resource = (new CreateTransactionAction())->execute($dto);

        if (!$resource->isSuccess()) {
            return $resource;
        }

        $transaction = $resource->getData();

        try {
            $gateway = (new PaymentGatewayFactory())->make($dto->gateway);


            if ($gateway instanceof CodGateway) {
                $processedTransaction = $gateway->processPayment($transaction);
                return new IntializePaymentSuccessResource($processedTransaction);
            }
            GatewayPaymentProcess::dispatch($gateway, $transaction)->onQueue('payments')->delay(now()->addSeconds(4));
            return new IntializePaymentSuccessResource();
        } catch (Exception $e) {
            return new IntializePaymentFailedResource();
        }
    }
}
