<?php

namespace Domain\order\Actions;

use Domain\Order\Models\OrderItem;
use Illuminate\Support\Collection;


class CreateOrderItemsAction
{
    public function __invoke(array $items, string $orderUuid, Collection $products): void
    {

        foreach ($items as $item) {
            $product = $products->get($item->productId);
            OrderItem::create([
                'order_uuid' => $orderUuid,
                'product_id' => $item->productId,
                'quantity' => $item->quantity,
                'price' => $product->price,
            ]);
        }
    }
}
