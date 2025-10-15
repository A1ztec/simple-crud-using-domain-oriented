<?php

namespace App\Jobs;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GatewayPaymentProcess implements ShouldQueue
{
    use Queueable;


    public $tries = 3;

    public $timeout = 120;

    public $backoff = [10, 40, 70];



    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }

    public function failed(Exception $e)
    {
        Log::error('Payment Job failed: ' . $e->getMessage());
    }
}
