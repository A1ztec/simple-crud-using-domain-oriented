<?php

namespace Domain\Order\Actions;

use Exception;
use Illuminate\Support\Str;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderItem;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Enums\StatusEnum;
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

                $existingOrder = Order::with('transaction')->where('user_id', Auth::id())
                    ->where('status', OrderStatusEnum::PENDING)
                    ->lockForUpdate()
                    ->first();

                if ($existingOrder) {

                    Log::info('Existing pending order found', [
                        'order_uuid' => $existingOrder->uuid,
                        'has_transaction' => $existingOrder->transaction ? true : false,
                        'transaction_status' => $existingOrder->transaction ? $existingOrder->transaction->status : null,
                    ]);

                    $transaction = $existingOrder->transaction;

                    if (!$transaction || $transaction->status == StatusEnum::EXPIRED) {
                        $transactionDto = new CreateTransactionDto(user_id: Auth::id(), amount: $existingOrder->total_amount, gateway: $dto->gateway, order_uuid: $existingOrder->uuid);

                        $resource = (new IntializePaymentAction())($transactionDto);

                        if (!$resource->isSuccess()) {
                            throw new Exception('Payment initialization failed: ' . $resource->getMessage());
                        }

                        $existingOrder->load(['items', 'transaction']);

                        $data = $resource->getData();
                        // $data['order'] = $order;

                        return new CreateOrderSuccessResource(data: $data, message: "New transaction created for existing pending order");
                    }

                    if (in_array($transaction->status, [StatusEnum::PENDING, StatusEnum::PROCESSING])) {
                        $data = ['checkout' => $transaction->paymentMethodGateway];
                        return new CreateOrderSuccessResource(data: $data, message: "you have an existing pending transaction for uncompleted order continue with that");
                    }
                }

                $productIds = array_map(fn($item) => $item->productId, $dto->items);
                sort($productIds);

                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get();

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

                $transactionDto = new CreateTransactionDto(user_id: Auth::id(), amount: $calculatedTotal, gateway: $dto->gateway, order_uuid: $orderUuid);

                $resource = (new IntializePaymentAction())($transactionDto);

                if (!$resource->isSuccess()) {
                    throw new Exception('Payment initialization failed: ' . $resource->getMessage());
                }

                $order->load(['items', 'transaction']);

                $data = $resource->getData();
                // $data['order'] = $order;

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
