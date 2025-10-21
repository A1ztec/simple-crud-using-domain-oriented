<?php

namespace Domain\Payment\Jobs;

use Exception;
use Domain\Payment\Enums\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Enums\StatusEnum;
use Illuminate\Support\Facades\Http;
use Domain\Payment\Models\Transaction;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RetryFailedTransactions implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $timeout = 120;

    public $backoff = [10, 30, 60];





    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // ToDo: Need to reFactor this


        //     try {
        //         DB::transaction(function () {
        //             $transactions =  Transaction::query()
        //                 ->whereIn('status', [StatusEnum::PROCESSING, StatusEnum::PENDING])
        //                 ->whereNotNull('gateway_response->session_id')
        //                 ->get();

        //             foreach ($transactions as $transaction) {

        //                 $gateway = $transaction->gateway;
        //                 $gateway_secret_key = config("services.$gateway.secret_key");
        //                 $gateway_base_url = config("services.$gateway.base_url");

        //                 try {
        //                     $response =  Http::withHeader('Authorization', 'Bearer ' . $gateway_secret_key)
        //                         ->get($gateway_base_url . '/v1/checkout/sessions/' . $transaction->gateway_response['session_id']);
        //                 } catch (Exception $e) {
        //                     Log::channel('payment')->error('RetryFailedTransactions Job failed: ' . $e->getMessage());
        //                     continue;
        //                 }

        //                 if ($response->successful()) {
        //                     $responseData = $response->json();

        //                     $status = $this->mapStatus($responseData['payment_status'] ?? null);

        //                     if (isset($responseData['payment_status'])) {
        //                         $gatewayResponse = $transaction->gateway_response ?? [];
        //                         $gatewayResponse['last_checked_at'] = now()->toDateTimeString();
        //                         $gatewayResponse['payment_status'] = $responseData['payment_status'];
        //                         $transaction->update([
        //                             'status' => $status,
        //                             'gateway_response' => $gatewayResponse,
        //                         ]);
        //                     }
        //                 } else {
        //                     $gatewayResponse = $transaction->gateway_response ?? [];
        //                     $gatewayResponse['last_checked_at'] = now()->toDateTimeString();
        //                     $transaction->update([
        //                         'gateway_response' => $gatewayResponse,
        //                     ]);
        //                 }
        //             }
        //         });
        //     } catch (Exception $e) {
        //         Log::channel('payment')->error('RetryFailedTransactions Job failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        //     }
        // }


        // private function mapStatus(?string $paymentStatus): string
        // {
        //     return match ($paymentStatus) {
        //         'paid' => StatusEnum::SUCCESS,
        //         'unpaid' => StatusEnum::PROCESSING,
        //         'failed' => StatusEnum::FAILED,
        //         default => StatusEnum::PENDING,
        //     };
        // }
    }
}
