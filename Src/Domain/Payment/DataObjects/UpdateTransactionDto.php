<?php


namespace Domain\Payment\DataObjects;


class UpdateTransactionDto
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $user_id = null,
        public readonly ?float $amount = null,
        public readonly ?string $status = null,
        public readonly ?string $reference_id = null,
        public readonly ?array $metadata = null,
        public readonly ?array $gateway_response = null
    ) {}
}
