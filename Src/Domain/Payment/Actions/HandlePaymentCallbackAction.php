<?php

namespace Domain\Payment\Actions;

use Exception;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Factories\PaymentGatewayFactory;
use Domain\Payment\DataObjects\HandleCallbackDto;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Domain\Payment\Resources\IntializePaymentFailedResource;

class HandlePaymentCallbackAction
{
    public function execute(HandleCallbackDto $dto): PaymentResourceInterface
    {
        try {
            $gateway = (new PaymentGatewayFactory())->make($dto->gateway);
            return $gateway->callBack($dto->payload);
        } catch (Exception $e) {
            Log::channel('payment')->error('Payment callback handling failed', [
                'error' => $e->getMessage(),
                'gateway' => $dto->gateway
            ]);
            return new IntializePaymentFailedResource();
        }
    }
}
