<?php


namespace Domain\Payment\DataObjects;


class UpdateTransactionDto
{
    public function __construct(
        public int $id,
        public ?float $amount = null,
        public ?string $status = null,
        public ?string $reference_id = null,
        public ?array $metadata = null,
        public ?array $gateway_response = null
    ) {}
}
