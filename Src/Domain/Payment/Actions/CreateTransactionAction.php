<?php


namespace Domain\Payment\Actions;

use Exception;
use Illuminate\Support\Str;
use Domain\Payment\Enums\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Enums\StatusEnum;
use Domain\Payment\Models\Transaction;
use Spatie\RouteAttributes\Attributes\Where;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\CreateTransactionFailedResource;
use Domain\Payment\Resources\CreateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class CreateTransactionAction

{
    public function __invoke(CreateTransactionDto $data): PaymentResourceInterface
    {
        if ($data->amount <= 0 || $data->user_id === null) {
            return new CreateTransactionFailedResource(
                message: 'Invalid transaction data'
            );
        }


        try {
            $exists = Transaction::where('amount', $data->amount)
                ->lockForUpdate()
                ->where('user_id', $data->user_id)
                ->where('gateway', $data->gateway)
                ->whereIn('status', [StatusEnum::PENDING, StatusEnum::PROCESSING])
                ->first();

            if ($exists) {
                DB::rollBack();
                return new CreateTransactionFailedResource(
                    message: 'Duplicate transaction detected'
                );
            }

            $gatewayValue = $data->gateway;
            $ReferenceId = strtoupper($gatewayValue . '_' . Str::uuid());

            $transaction = Transaction::create([
                'user_id' => $data->user_id,
                'amount' => $data->amount,
                'gateway' => $gatewayValue,
                'status' => $data->status ?? StatusEnum::PENDING,
                'reference_id' => $ReferenceId,
            ]);


            return new CreateTransactionSuccessResource($transaction);
        } catch (Exception $e) {
            Log::error('Transaction creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return new CreateTransactionFailedResource();
        }
    }
}
