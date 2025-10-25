<?php

namespace Domain\Order\Actions;

use Exception;
use Illuminate\Support\Str;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderItem;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\DataObjects\CreateOrderDto;
use Domain\Payment\Actions\IntializePaymentAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Order\Resources\CreateOrderFailedResource;
use Domain\Order\Resources\CreateOrderSuccessResource;
use Domain\Order\Resources\Contracts\OrderResourceInterface;

class CreateOrderAction
{
    public function __invoke(CreateOrderDto $dto): OrderResourceInterface
    {
        try {
            return DB::transaction(function () use ($dto) {

                $existingOrder = Order::where('user_id', Auth::id())
                    ->where('status', OrderStatusEnum::PENDING)
                    ->lockForUpdate()
                    ->first();

                if ($existingOrder) {
                    throw new Exception('You have a pending order. Please complete or cancel it before creating a new one.');
                }

                
                $productIds = array_map(fn($item) => $item->productId, $dto->items);
                sort($productIds);
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $validationResult = (new ValidateOrderCreationData())($dto, $products);
                if (!$validationResult->isSuccess()) {
                    throw new Exception($validationResult->getMessage());
                }

                $calculatedTotal = $validationResult->getData()['calculated_total'];

                foreach ($dto->items as $item) {
                    $product = $products->get($item->productId);
                    $product->decrement('quantity', $item->quantity);
                }

                $orderUuid = (string) Str::uuid();
                $order = Order::create([
                    'uuid' => $orderUuid,
                    'user_id' => Auth::id(),
                    'total_amount' => $calculatedTotal,
                    'status' => OrderStatusEnum::PENDING,
                    'shipping_address' => $dto->shippingAddress,
                ]);

                $order->refresh();

                foreach ($dto->items as $item) {
                    $product = $products->get($item->productId);
                    OrderItem::create([
                        'order_uuid' => $orderUuid,
                        'product_id' => $item->productId,
                        'product_name' => $product->name,
                        'quantity' => $item->quantity,
                        'price' => $product->price,
                    ]);
                }

                $transactionDto = new CreateTransactionDto(
                    user_id: Auth::id(),
                    amount: $calculatedTotal,
                    gateway: $dto->gateway,
                    order_uuid: $orderUuid
                );

                $resource = (new IntializePaymentAction())($transactionDto);

                if (!$resource->isSuccess()) {
                    throw new Exception('Payment initialization failed: ' . $resource->getMessage());
                }

                $order->load(['items', 'transaction']);

                $data = $resource->getData();
                $data['order'] = $order;

                return new CreateOrderSuccessResource(data: $data);
            });
        } catch (Exception $e) {
            Log::error('Order creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return new CreateOrderFailedResource(
                message: $e->getMessage()
            );
        }
    }
}
