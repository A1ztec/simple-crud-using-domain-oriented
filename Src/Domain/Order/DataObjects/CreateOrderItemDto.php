<?php

namespace Domain\Order\DataObjects;

class CreateOrderItemDto
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly float $price,
    ) {}
}
