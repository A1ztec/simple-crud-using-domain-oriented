<?php


namespace Domain\Payment\Actions;

use Exception;
use Domain\Payment\Enums\Status;
use Domain\Payment\Models\Transaction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\CreateTransactionFailedResource;
use Domain\Payment\Resources\CreateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class CreateTransactionAction

{
    public function execute(CreateTransactionDto $data): PaymentResourceInterface
    {
        // Logic to create a transaction
        try {
            $transaction = Transaction::create([
                'user_id' => $data->user_id,
                'amount' => $data->amount,
                'gateway' => $data->gateway,
                'status' => $data->status ?? Status::PENDING->value,
            ]);
            return new CreateTransactionSuccessResource($transaction);
        } catch (Exception $e) {
            return new CreateTransactionFailedResource();
        }
    }
}
