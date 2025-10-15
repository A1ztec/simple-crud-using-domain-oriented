<?php


namespace Domain\Payment\Actions;


use App\Jobs\GatewayPaymentProcess;
use Domain\Payment\Gateways\CodGateway;
use Domain\Payment\Factories\PaymentGatewayFactory;
use Domain\Payment\DataObjects\CreateTransactionDto;

class IntializePaymentAction
{



    public function execute(CreateTransactionDto $dto, CreateTransactionAction $action)
    {

        $resource = $action->execute($dto);
        if (!$resource->isSuccess()) {
            return $resource;
        }
        $data = $resource->getData();
        $gateway = new PaymentGatewayFactory()->make($dto->gateway);
        if (!$gateway instanceof CodGateway) {
            GatewayPaymentProcess::dispatch($gateway, $data)->onQueue('payments');
        }

        return $gateway->processPayment($data);
    }
}
