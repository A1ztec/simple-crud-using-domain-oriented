<?php


namespace Domain\Payment\Actions;

use Exception;
use Illuminate\Support\Str;
use Domain\Payment\Enums\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Models\Transaction;
use Spatie\RouteAttributes\Attributes\Where;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\Resources\CreateTransactionFailedResource;
use Domain\Payment\Resources\CreateTransactionSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class CreateTransactionAction

{
    public function execute(CreateTransactionDto $data): PaymentResourceInterface
    {
        DB::beginTransaction();
        try {
            $exists = Transaction::where('amount', $data->amount)
                ->where('user_id', $data->user_id)
                ->where('gateway', $data->gateway)
                ->whereIn('status', [Status::PENDING, Status::PROCESSING])
                ->lockForUpdate()
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
                'status' => $data->status ?? Status::PENDING->value,
                'reference_id' => $ReferenceId,
                'metadata' => null,
                'gateway_response' => null,
            ]);

            DB::commit();
            return new CreateTransactionSuccessResource($transaction);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return new CreateTransactionFailedResource();
        }
    }
}
