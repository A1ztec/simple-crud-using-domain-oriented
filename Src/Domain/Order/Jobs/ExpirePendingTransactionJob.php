<?php

namespace Domain\Order\Jobs;

use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Enums\StatusEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ExpirePendingTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    /**
     * Create a new job instance.
     */
    public function __construct(private int $transactionId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        DB::transaction(function () {
            $transaction = Transaction::with(['order', 'order.items'])
                ->where('id', $this->transactionId)
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                Log::warning("ExpireTransactionJob: transaction {$this->transactionId} not found");
                return;
            }


            if ($transaction->status !== StatusEnum::PENDING) {
                Log::info("ExpireTransactionJob: transaction {$transaction->id} already processed ({$transaction->status->value})");
                return;
            }


            $transaction->update(['status' => StatusEnum::EXPIRED]);


            if ($transaction->paymentMethodGateway) {
                $transaction->paymentMethodGateway->update(['status' => StatusEnum::EXPIRED]);
            }


            foreach ($transaction->order->items as $item) {
                Product::where('id', $item->product_id)->increment('quantity', $item->quantity);
            }

            Log::info("ExpireTransactionJob: transaction {$transaction->id} expired and product quantities restored");
        });
    }
}
