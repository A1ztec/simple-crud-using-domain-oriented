<?php


namespace Domain\Payment\DataObjects;


class UpdateTransactionDto
{
    public function __construct(
        public ?float $amount = null,
        public ?string $status = null,
        public ?string $referenceId = null,
        public ?array $metadata = null,
        public ?array $gatewayResponse = null
    ) {}
}
