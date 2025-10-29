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
use Domain\Order\Resources\InitializeOrderPaymentFailedResource;
use Domain\Order\Resources\InitializeOrderPaymentSuccessResource;
use Illuminate\Validation\Rules\In;

class InitializeOrderPaymentAction
{
    public function __invoke($order, $gateway)
    {
        try {

            $transaction = $order->transaction;

            if (!$transaction || $transaction->status === StatusEnum::EXPIRED) {

                $order->unsetRelation('transaction');

                $data = [
                    'user_id' => Auth::id(),
                    'amount' => $order->total_amount,
                    'gateway' => $gateway,
                    'order_uuid' => $order->uuid
                ];

                $transactionDto = CreateTransactionDto::setData($data);

                $resource = (new IntializePaymentAction())($transactionDto);

                if (!$resource->isSuccess()) {
                    throw new Exception('Payment initialization failed: ' . $resource->getMessage());
                }

                return new InitializeOrderPaymentSuccessResource(data: ['transaction' => $resource->getData()]);
            }

            if (in_array($transaction->status, [StatusEnum::PENDING, StatusEnum::PROCESSING])) {

                $data = ['checkout_url' => $transaction->paymentMethodGateway->checkout_url, 'reference_id' => $transaction->reference_id];
                return new InitializeOrderPaymentSuccessResource(data: ['transaction' => $data]);
            }
        } catch (Exception $e) {
            Log::error('Handling order transaction failed', ['error' => $e->getMessage(), 'order_uuid' => $order->uuid]);
            return new InitializeOrderPaymentFailedResource();
        }
    }
}
