<?php


namespace Domain\Payment\DataObjects;


class ShowTransactionDto
{
    public function __construct(
        public string $reference_id,
    ) {}
}
