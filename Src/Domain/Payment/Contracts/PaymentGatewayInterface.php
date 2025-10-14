<?php

namespace Domain\Payment\Contracts;


interface PaymentGatewayInterface
{
    public function processPayment(array $data): array;

    public function getGatewayName(): string;

    public function validateTransactionData(array $data): bool;
}
