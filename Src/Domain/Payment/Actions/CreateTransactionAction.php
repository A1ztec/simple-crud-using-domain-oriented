<?php


namespace Domain\Payment\Actions;

use Exception;
use Domain\Payment\Enums\Status;
use Domain\Payment\Models\Transaction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\CreateTransactionFailedResource;
use Domain\Payment\Resources\CreateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Spatie\RouteAttributes\Attributes\Where;

class CreateTransactionAction

{
    public function execute(CreateTransactionDto $data): PaymentResourceInterface
    {
        //toDo : prevent duplicate transaction for same user with same amount and same gateway


        //simulation of duplicate transaction prevention logic

        $exists = Transaction::where('amount', $data->amount)
            ->where('user_id', $data->user_id)
            ->where('gateway', $data->gateway)
            ->whereIn('status', [Status::PENDING, Status::PROCESSING])
            ->lockForUpdate()
            ->first();

        if ($exists) {
            return new CreateTransactionFailedResource(message: 'Duplicate transaction detected for the same user with the same amount and gateway');
        }

        $gatewayValue = $data->gateway;
        $data->reference_id = strtoupper(uniqid($gatewayValue . '_'));


        try {
            $transaction = Transaction::create([
                'user_id' => $data->user_id,
                'amount' => $data->amount,
                'gateway' => $gatewayValue,
                'status' => $data->status ?? Status::PENDING->value,
                'reference_id' => $data->reference_id,
                'metadata' => null,
                'gateway_response' => null,
            ]);
            return new CreateTransactionSuccessResource($transaction);
        } catch (Exception $e) {
            return new CreateTransactionFailedResource();
        }
    }
}
