<?php


namespace Domain\Payment\DataObjects;


class ShowTransactionDto
{
    public function __construct(
        public readonly string $reference_id,
    ) {}
}
