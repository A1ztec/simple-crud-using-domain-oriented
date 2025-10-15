<?php

namespace App\Jobs;

use Exception;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Models\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Domain\Payment\Contracts\PaymentGatewayInterface;

class GatewayPaymentProcess implements ShouldQueue
{
    use Queueable;


    public $tries = 3;

    public $timeout = 120;

    public $backoff = [10, 40, 70];



    /**
     * Create a new job instance.
     */
    public function __construct(private PaymentGatewayInterface $gateway, private Transaction $transaction)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->gateway->processPayment($this->transaction);

       
    }

    public function failed(Exception $e)
    {
        Log::error('Payment Job failed: ' . $e->getMessage());
    }
}
