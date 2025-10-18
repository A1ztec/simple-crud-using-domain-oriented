<?php


namespace Domain\Payment\DataObjects;

class CreateTransactionDto
{
    public function __construct(
        public readonly int $user_id,
        public readonly float $amount,
        public readonly string $gateway,
        public readonly ?string $reference_id = null,
        public readonly ?string $status = null,
    ) {}
}
