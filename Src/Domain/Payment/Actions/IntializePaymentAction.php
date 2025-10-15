<?php


namespace Domain\Payment\Actions;


use Exception;
use Faker\Provider\ar_EG\Payment;
use App\Jobs\GatewayPaymentProcess;
use Domain\Payment\Gateways\CodGateway;
use Domain\Payment\Factories\PaymentGatewayFactory;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\UpdateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class IntializePaymentAction
{



    public function execute(CreateTransactionDto $dto): PaymentResourceInterface
    {

        $resource = new CreateTransactionAction()->execute($dto);
        if (!$resource->isSuccess()) {
            return $resource;
        }
        $data = $resource->getData();
        $gateway = new PaymentGatewayFactory()->make($dto->gateway);
        if (!$gateway instanceof CodGateway) {
            GatewayPaymentProcess::dispatch($gateway, $data)->onQueue('payments');
            return new IntializePaymentSuccessResource();
        }

        try {
            $transaction = $gateway->processPayment($data);
            return new IntializePaymentSuccessResource($transaction);
        } catch (Exception $e) {
            return new IntializePaymentFailedResource();
        }
    }
}
