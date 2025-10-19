<?php


namespace Domain\Payment\Factories;

use InvalidArgumentException;
use Domain\Payment\Enums\Gateway;
use Domain\Payment\Gateways\CodGateway;
use Domain\Payment\Gateways\StripeGateway;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Contracts\OnlinePaymentGatewayInterface;

class PaymentGatewayFactory
{
    public function make(string $gateway): PaymentGatewayInterface|OnlinePaymentGatewayInterface
    {
        return match ($gateway) {
            Gateway::STRIPE->value => new StripeGateway(),
            Gateway::COD->value => new CodGateway(),
            default => throw new InvalidArgumentException("no match Gateway"),
        };
    }
}
