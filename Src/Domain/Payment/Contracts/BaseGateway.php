<?php

namespace Domain\Payment\Contracts;

use Illuminate\Validation\Rules\Unique;

abstract class BaseGateway implements PaymentGatewayInterface
{
    public function generateReferenceId(): string
    {
        return strtoupper(uniqid($this->getGatewayName() . '_'));
    }

    
}
