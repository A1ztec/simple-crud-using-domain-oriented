<?php


namespace Domain\Payment\Actions;

use Exception;
use Domain\Payment\Models\Transaction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Resources\UpdateTransactionFailedResource;
use Domain\Payment\Resources\UpdateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class UpdateTransactionAction

{
    public function execute(UpdateTransactionDto $data): PaymentResourceInterface
    {

        try {
            $transaction = Transaction::findOrFail($data->id);
            $transaction->update([
                'amount' => $data->amount ?? $transaction->amount,
                'status' => $data->status ?? $transaction->status,
                'reference_id' => $data->reference_id ?? $transaction->reference_id,
                'metadata' => $data->metadata ?? $transaction->metadata,
                'gateway_response' => $data->gateway_response ?? $transaction->gateway_response,
            ]);
            return new UpdateTransactionSuccessResource($transaction);
        } catch (Exception $e) {
            return new UpdateTransactionFailedResource();
        }
    }
}
