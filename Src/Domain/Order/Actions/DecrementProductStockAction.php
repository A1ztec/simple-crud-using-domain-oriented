<?php

namespace Domain\Order\Actions;

use Domain\Order\Models\Order;
use Domain\Order\Resources\Contracts\OrderResourceInterface;
use Domain\Order\Resources\DecrementProductStockResource;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DecrementProductStockAction
{

    public function __invoke($items): OrderResourceInterface
    {
        return DB::transaction(function () use ($items) {

            $stockShortage = false;

            $productIds = $items->pluck('product_id')->toArray();

            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {

                $product = $products->get($item->product_id);

                if (!$product) {
                    $stockShortage = true;
                    continue;
                }

                $remaining = $product->quantity - $item->quantity;

                if ($remaining < 0) {
                    $stockShortage = true;
                }

                $product->update(['quantity' => $remaining]);
            }
            return new DecrementProductStockResource(data: ['stockShortage' => $stockShortage]);
        });
    }
}
