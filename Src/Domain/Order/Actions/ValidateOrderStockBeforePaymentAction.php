<?php

namespace Domain\Order\Actions;

use Domain\Order\Models\Order;
use Domain\Order\Resources\Contracts\OrderResourceInterface;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Log;
use Domain\Order\Resources\ValidateOrderStockFailedResource;
use Domain\Order\Resources\ValidateOrderStockSuccessResource;

class ValidateOrderStockBeforePaymentAction
{
    public function __invoke(Order $order): OrderResourceInterface
    {
        foreach ($order->items as $item) {
            $product = Product::find($item->productId);

            if (!$product || $product->quantity < $item->quantity) {
                Log::warning('Insufficient stock during order validation', [
                    'order_uuid' => $order->uuid,
                    'product_id' => $item->productId,
                    'available' => $product ? $product->quantity : 0,
                    'requested' => $item->quantity,
                ]);
                $order->update(['status' => 'expired']);
                return new ValidateOrderStockFailedResource();
            }
        }


        return new ValidateOrderStockSuccessResource();
    }
}
