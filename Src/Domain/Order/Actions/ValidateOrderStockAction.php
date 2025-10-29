<?php

namespace Domain\Order\Actions;

use Domain\Order\DataObjects\CreateOrderDto;
use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Domain\Order\Resources\ValidateOrderStockFailedResource;
use Domain\Order\Resources\ValidateOrderStockSuccessResource;
use Domain\Order\Resources\Contracts\OrderResourceInterface;
use Illuminate\Support\Facades\Log;

class ValidateOrderStockAction
{
    public function __invoke(Order|CreateOrderDto $source): OrderResourceInterface
    {

        $items = $source instanceof Order
            ? $source->items->map(fn($item) => (object)[
                'productId' => $item->product_id,
                'quantity'  => $item->quantity
            ])
            : collect($source->items);

        $productIds = $items->pluck('productId')->toArray();

        $products = Product::whereIn('id', $productIds)
            ->get(['id', 'quantity'])
            ->keyBy('id');

        foreach ($items as $item) {
            $product = $products->get($item->productId);

            if (!$product || $product->quantity < $item->quantity) {
                return new ValidateOrderStockFailedResource();
            }
        }

        return new ValidateOrderStockSuccessResource();
    }
}
