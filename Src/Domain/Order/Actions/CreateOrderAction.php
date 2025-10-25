<?php

namespace Domain\Order\Actions;

use Illuminate\Support\Str;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderItem;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\DataObjects\CreateOrderDto;
use Domain\Order\Resources\Contracts\OrderResourceInterface;
use Domain\Payment\Actions\IntializePaymentAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Order\Resources\CreateOrderFailedResource;
use Domain\Order\Resources\CreateOrderSuccessResource;

class CreateOrderAction
{
    public function __invoke(CreateOrderDto $dto): OrderResourceInterface
    {
        $order = Order::where('user_id', Auth::id())->where('status', OrderStatusEnum::PENDING)->first();
        if ($order) {
            return new CreateOrderFailedResource(message: 'You have a pending order. Please complete or cancel it before creating a new one.');
        }

        return DB::transaction(function () use ($dto) {

            $productIds = array_map(fn($item) => $item->productId, $dto->items);
            sort($productIds);
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');


            $validationResult = (new ValidateOrderCreationData())($dto, $products);
            if (!$validationResult->isSuccess()) {
                return new CreateOrderFailedResource(message: $validationResult->getMessage());
            }

            $calculatedTotal = $validationResult->getData()['calculated_total'];

            foreach ($dto->items as $item) {
                $products->get($item->productId)->decrement('quantity', $item->quantity);
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

            // dd($order->uuid);

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

            $transactionDto = new CreateTransactionDto(user_id: Auth::id(), amount: $calculatedTotal, gateway: $dto->gateway, order_uuid: $orderUuid);
            try {
                $resource = (new IntializePaymentAction())($transactionDto);
            } catch (\Exception $e) {
                Log::error('Order creation failed due to transaction initialization failure', ['order_uuid' => $orderUuid, 'error' => $e->getMessage()]);
                return new CreateOrderFailedResource(message: 'Order creation failed');
            }
            $data = $resource->getData();
            return new CreateOrderSuccessResource(data: $data);
        });
    }
}
