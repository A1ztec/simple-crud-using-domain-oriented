<?php

namespace Domain\Order\Actions;

use Exception;
use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\DataObjects\CreateOrderDto;
use Domain\Order\Resources\CreateOrderFailedResource;
use Domain\Order\Resources\CreateOrderSuccessResource;
use Application\Product\QueryBuilders\ProductQueryBuilder;
use Domain\Order\Resources\CheckForPendingOrderSuccessResource;
use Domain\Order\Resources\Contracts\OrderResourceInterface;
use Tymon\JWTAuth\Http\Middleware\Check;

class CreateOrderAction
{
    public function __invoke(CreateOrderDto $dto): OrderResourceInterface
    {
        try {
            return DB::transaction(function () use ($dto): OrderResourceInterface {

                $existingOrder = (new CheckForPendingOrderAction())($dto);
                if ($existingOrder->isSuccess()) {
                    return new CheckForPendingOrderSuccessResource(data: $existingOrder->getData());
                }
                $products = $this->getProductsByIds($dto->items);
                $ValidateOrderCreationData = (new ValidateOrderCreationData())($dto, $products);

                if (!$ValidateOrderCreationData->isSuccess()) {
                    throw new Exception($ValidateOrderCreationData->getMessage());
                }

                $calculatedTotal = $this->calculateAndReserveStock($dto->items, $products);

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $calculatedTotal,
                    'status' => OrderStatusEnum::PENDING,
                    'shipping_address' => $dto->shippingAddress,
                ]);

                (new CreateOrderItemsAction())($dto->items, $order->uuid, $products);

                $resource = (new HandleOrderTransactionAction())($order, $dto->gateway);

                return new CreateOrderSuccessResource(data: ['order' => $order, 'transaction' => $resource->getData()['transaction']]);
            });
        } catch (Exception $e) {
            Log::error('Order creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return new CreateOrderFailedResource(message: $e->getMessage());
        }
    }

    private function getProductsByIds(array $items)
    {
        $ids = array_map(fn($item) => $item->productId, $items);
        sort($ids);
        return (new ProductQueryBuilder())->getProductsByIds($ids);
    }

    private function calculateAndReserveStock(array $items, $products): float
    {
        $calculatedTotal = 0;

        foreach ($items as $item) {
            $product = $products->get($item->productId);
            $product->decrement('quantity', $item->quantity);
            $calculatedTotal += $product->price * $item->quantity;
        }

        return $calculatedTotal;
    }
}
