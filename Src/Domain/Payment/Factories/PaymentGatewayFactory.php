<?php


namespace Domain\Payment\Factories;

use InvalidArgumentException;
use Domain\Payment\Enums\GatewayEnum;
use Domain\Payment\Gateways\CodGateway;
use Domain\Payment\Gateways\StripeGateway;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Contracts\OnlinePaymentGatewayInterface;

class PaymentGatewayFactory
{
    public function make(string $gateway): PaymentGatewayInterface|OnlinePaymentGatewayInterface
    {
        return match ($gateway) {
            GatewayEnum::STRIPE => new StripeGateway(),
            GatewayEnum::COD => new CodGateway(),
            default => throw new InvalidArgumentException("no match Gateway"),
        };
    }
}
