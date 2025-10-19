<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use Illuminate\Support\Facades\Http;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Contracts\OnlinePaymentGatewayInterface;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Domain\Payment\Resources\OnlinePaymentInitializeSuccessResource;

class StripeGateway  implements PaymentGatewayInterface, OnlinePaymentGatewayInterface
{
    private string $baseUrl;
    private array $header;

    private string $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('services.stripe.base_url');
        $this->secretKey = config('services.stripe.secret_key');
        $this->header = [
            'Authorization' => 'bearer ' . $this->secretKey,
            'content-type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ];
    }
    public function validateTransactionData(Transaction $transaction): bool
    {
        return $transaction->amount > 0 && $transaction->user_id !== null;
    }

    public function processPayment(Transaction $transaction): PaymentResourceInterface
    {

        $data = $this->formatData($transaction);
        $response = Http::withHeader(...($this->header))
            ->post($this->baseUrl . '/v1/checkout/sessions', $data);
        if ($response->getData(true)['success']) {

            $data = [
                'checkout_url' => $response->getData(true)['data']['url'],
                'session_id' => $response->getData(true)['data']['id'],
                'status' => $response->getData(true)['data']['status'],
            ];
            return new IntializePaymentSuccessResource(
                data: $data,
            );
        } else {
            Log::error('Stripe payment initialization failed', [
                'response' => $response->getData(true),
                'transaction_id' => $transaction->id,
            ]);
            $transaction->update(['status' => Status::FAILED]);
            return new IntializePaymentFailedResource();
        }
    }

    public function getGatewayName(): string
    {
        return Gateway::STRIPE->value;
    }

    // toDo : callback here to handle the callback returned from the payment gateway

    public function callBack(): array
    {

        // toDo : implement callback handling logic
        return [];
    }

    public function formatData(Transaction $transaction): array
    {
        return [
            'success_url' => 'api/v1/payment/callback?SESSION_ID={CHECKOUT_SESSION_ID}',
            'amount' => $transaction->amount,
            'currency' => 'usd',
            'reference_id' => $transaction->reference_id,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Payment for order ' . $transaction->reference_id,
                        ],
                        'unit_amount' => $transaction->amount * 100,
                    ],
                    'quantity' => 1,
                ],
                'mode' => 'payment',
            ],
        ];
    }
}
