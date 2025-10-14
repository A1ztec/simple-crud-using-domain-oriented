<?php


namespace Domain\Payment\Factories;

use Domain\Payment\Enums\Gateway;
use Domain\Payment\Gateways\CodGateway;
use Domain\Gateways\Gateways\StripeGateway;
use Domain\Payment\Contracts\PaymentGatewayInterface;

class PaymentGatewayFactory
{
    public function __invoke(string $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            Gateway::STRIPE->value => new StripeGateway(),
            Gateway::COD->value => new CodGateway(),
            default => 'invalid gateway',
        };
    }
}
