<?php


namespace Domain\Payment\Actions;

use Exception;
use Illuminate\Support\Str;
use Domain\Order\Models\Order;
use Domain\Payment\Enums\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Enums\StatusEnum;
use Domain\Payment\Models\Transaction;
use Spatie\RouteAttributes\Attributes\Where;
use Domain\Order\Jobs\ExpirePendingTransactionJob;
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

            Order::where('uuid', $data->order_uuid)->lockForUpdate()->first();

            $transaction = Transaction::where('order_uuid', $data->order_uuid)
                ->whereIn('status', [StatusEnum::PENDING, StatusEnum::PROCESSING])
                ->first();

            if ($transaction) {
                return new CreateTransactionSuccessResource(data: $transaction);
            }

            $gatewayValue = $data->gateway;
            $ReferenceId = strtoupper($gatewayValue . '_' . Str::uuid());

            $transaction = Transaction::create([
                'user_id' => $data->user_id,
                'order_uuid' => $data->order_uuid,
                'amount' => $data->amount,
                'gateway' => $gatewayValue,
                'status' => $data->status ?? StatusEnum::PENDING,
                'reference_id' => $ReferenceId,
            ]);

            ExpirePendingTransactionJob::dispatch($transaction->id)->onQueue('payment')
                ->delay(now()->addMinutes(10));


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
