<?php

namespace Domain\Order\DataObjects;


class CreateOrderDto
{
    public function __construct(
        public readonly array $items,
        public readonly ?float $totalAmount = null,
        public readonly ?string $shippingAddress = null,
        public readonly string $gateway,
    ) {}
}
