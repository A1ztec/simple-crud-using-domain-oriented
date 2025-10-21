<?php


namespace Domain\Payment\Actions;

use Exception;
use Domain\Payment\Models\Transaction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Resources\UpdateTransactionFailedResource;
use Domain\Payment\Resources\UpdateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Illuminate\Support\Facades\Log;

class UpdateTransactionAction

{
    public function __invoke(UpdateTransactionDto $data, Transaction $transaction): PaymentResourceInterface
    {
        try {
            // ToDo: no need to this check i will pass the transaction directly
            $transaction->update([
                'status' => $data->status,
                'payment_method_gateway_id' => $data->payment_method_gateway_id,
                'payment_method_gateway_type' => $data->payment_method_gateway_type
            ]);
            return new UpdateTransactionSuccessResource($transaction);
        } catch (Exception $e) {
            Log::channel('payment')->error('Transaction update failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            return new UpdateTransactionFailedResource();
        }
    }
}
