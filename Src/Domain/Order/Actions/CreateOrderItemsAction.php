<?php

namespace Domain\order\Actions;

use Domain\Order\Models\OrderItem;
use Illuminate\Support\Collection;


class CreateOrderItemsAction
{
    public function __invoke(array $items, string $orderUuid, Collection $products): void
    {

        $orderItems = collect($items)->map(function ($item) use ($products, $orderUuid) {
            $product = $products->get($item->productId);
            return [
                'order_uuid' => $orderUuid,
                'product_id' => $item->productId,
                'quantity' => $item->quantity,
                'price' => $product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        OrderItem::insert($orderItems);
    }
}
