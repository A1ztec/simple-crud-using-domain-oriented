<?php

namespace Domain\Order\Actions;

use Exception;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Enums\StatusEnum;
use Illuminate\Support\Facades\Auth;
use Domain\Payment\Actions\IntializePaymentAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Order\Resources\HandleOrderTransactionFailedResource;
use Domain\Order\Resources\HandleOrderTransactionSuccessResource;

class HandleOrderTransactionAction
{
    public function __invoke($order, $gateway)
    {
        $stockValid = (new ValidateOrderStockBeforePaymentAction())($order);
        if (!$stockValid->isSuccess()) {
            return $stockValid;
        }

        $transaction = $order->transaction;

        if (!$transaction || $transaction->status === StatusEnum::EXPIRED) {
            $order->unsetRelation('transaction');
            $data = ['user_id' => Auth::id(), 'amount' => $order->total_amount, 'gateway' => $gateway, 'order_uuid' => $order->uuid];
            $transactionDto = CreateTransactionDto::setData($data);
            $resource = (new IntializePaymentAction())($transactionDto);
            if (!$resource->isSuccess()) {
                throw new Exception('Payment initialization failed: ' . $resource->getMessage());
            }
            Log::info('Created new transaction for order', ['order_uuid' => $order->uuid, 'transaction' => $resource->getData()]);
            return new HandleOrderTransactionSuccessResource(data: ['transaction' => $resource->getData()]);
        }
        if (in_array($transaction->status, [StatusEnum::PENDING, StatusEnum::PROCESSING])) {
            Log::info('Using existing pending/processing transaction', ['transaction' => $transaction]);
            $transaction->load('paymentMethodGateway');
            return new HandleOrderTransactionSuccessResource(data: ['transaction' => $transaction->paymentMethodGateway]);
        }

        return new HandleOrderTransactionFailedResource();
    }
}
