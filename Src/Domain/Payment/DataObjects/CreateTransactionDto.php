<?php


namespace Domain\Payment\DataObjects;

class CreateTransactionDto
{
    public function __construct(
        public int $user_id,
        public float $amount,
        public string $gateway,
        public ?string $status = null,
    ) {}
}
