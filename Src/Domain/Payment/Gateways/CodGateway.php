<?php

namespace Domain\Payment\Gateways;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Enums\StatusEnum;
use Domain\Payment\Enums\GatewayEnum;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Models\CodPaymentTransaction;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class CodGateway implements PaymentGatewayInterface
{
    public function processPayment(Transaction $transaction): PaymentResourceInterface
    {
        try {


            $codTransaction =  CodPaymentTransaction::create([
                'payment_id' => $transaction->id,
                'amount' => $transaction->amount,
                'status' => StatusEnum::SUCCESS,
            ]);

            $transaction->update([
                'payment_method_gateway_id' => $codTransaction->id,
                'payment_method_gateway_type' => $this->getGatewayName(),
                'status' => StatusEnum::SUCCESS,
            ]);

            return new IntializePaymentSuccessResource(
                data: [
                    'reference_id' => $transaction->reference_id,
                    'amount' => $transaction->amount,
                ],
                message: 'Cash on Delivery payment processed successfully'
            );
        } catch (Exception $e) {
            Log::channel('payment')->error('COD payment processing failed', ['error' => $e->getMessage(), 'transaction_id' => $transaction->id]);
            return new IntializePaymentFailedResource();
        }
    }

    public function getGatewayName(): string
    {
        return GatewayEnum::COD;
    }
}
