<?php

namespace Domain\Order\DataObjects;


class CreateOrderDto
{
    public function __construct(
        public readonly array $items,
        public readonly ?string $shippingAddress,
        public readonly string $gateway,
    ) {}

    public static function fromRequest(array $data): self
    {
        $items = array_map(function ($item) {
            return new CreateOrderItemDto(
                productId: $item['product_id'],
                quantity: $item['quantity'],
            );
        }, $data['items']);

        return new self(
            items: $items,
            shippingAddress: $data['shippingAddress'] ?? null,
            gateway: $data['gateway'],
        );
    }
}
