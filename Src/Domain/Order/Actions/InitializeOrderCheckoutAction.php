<?php

namespace Domain\Order\Actions;

use Exception;
use Domain\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\DataObjects\CreateOrderDto;
use Application\Product\QueryBuilders\ProductQueryBuilder;
use Domain\Order\Resources\Contracts\OrderResourceInterface;
use Domain\Order\Resources\InitializeOrderCheckoutFailedResource;
use Domain\Order\Resources\InitializeOrderPaymentSuccessResource;
use Domain\Order\Resources\ProcessPendingOrderSuccessResource;

class InitializeOrderCheckoutAction
{
    public function __invoke(CreateOrderDto $dto): OrderResourceInterface
    {
        try {
            return DB::transaction(function () use ($dto): OrderResourceInterface {

                $existingOrder = (new ProcessPendingOrderAction())($dto);

                if ($existingOrder->isSuccess()) {
                    return new ProcessPendingOrderSuccessResource(data: $existingOrder->getData());
                }

                $products = $this->getProductsByIds($dto->items);

                $ValidateOrderStockData = (new ValidateOrderStockAction())($dto);

                if (!$ValidateOrderStockData->isSuccess()) {

                    return $ValidateOrderStockData;
                }

                $calculatedTotal = $this->calculateTotal($dto->items, $products);

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $calculatedTotal,
                    'status' => OrderStatusEnum::PENDING,
                    'shipping_address' => $dto->shippingAddress
                ]);

                (new CreateOrderItemsAction())($dto->items, $order->uuid, $products);

                $resource = (new InitializeOrderPaymentAction())($order, $dto->gateway);

                return new InitializeOrderPaymentSuccessResource(data: ['transaction' => $resource->getData()['transaction']]);
            });
        } catch (Exception $e) {
            Log::error('Order creation failed', ['error' => $e->getMessage(), 'line' => $e->getLine()]);
            return new InitializeOrderCheckoutFailedResource();
        }
    }


    private function getProductsByIds(array $items)
    {
        $ids = array_map(fn($item) => $item->productId, $items);
        sort($ids);
        return (new ProductQueryBuilder())->getProductsByIds($ids);
    }

    private function calculateTotal(array $items, $products): float
    {
        $calculatedTotal = 0;
        foreach ($items as $item) {
            $product = $products->get($item->productId);
            $calculatedTotal += $product->price * $item->quantity;
        }

        return $calculatedTotal;
    }
}
