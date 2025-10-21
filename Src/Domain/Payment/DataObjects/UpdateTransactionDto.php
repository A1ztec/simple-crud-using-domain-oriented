<?php


namespace Domain\Payment\DataObjects;


class UpdateTransactionDto
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?int $user_id = null,
        public readonly ?float $amount = null,
        public readonly ?string $status = null,
        public readonly ?string $reference_id = null,
        public readonly ?int $payment_method_gateway_id = null,
        public readonly ?string $payment_method_gateway_type = null
    ) {}
}
