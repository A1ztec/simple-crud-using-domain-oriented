<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Contracts\OnlinePaymentGatewayInterface;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;

class StripeGateway implements PaymentGatewayInterface, OnlinePaymentGatewayInterface
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
        if (!$this->validateTransactionData($transaction)) {
            $transaction->update(['status' => Status::FAILED]);
            return new IntializePaymentFailedResource();
        }

        $data = $this->formatData($transaction);
        $response = Http::withHeader(...($this->header))
            ->post($this->baseUrl . '/v1/checkout/sessions', $data);

        if ($response->getData(true)['success'] ?? false) {
            $gatewayData = [
                'checkout_url' => $response->getData(true)['data']['url'],
                'session_id' => $response->getData(true)['data']['id'],
                'status' => $response->getData(true)['data']['status'],
            ];

            $transaction->update([
                'status' => Status::PENDING,
                'gateway_response' => [
                    'gateway' => $this->getGatewayName(),
                    'session_id' => $gatewayData['session_id'],
                    'initiated_at' => now()->toDateTimeString(),
                ]
            ]);

            return new IntializePaymentSuccessResource(
                data: $gatewayData,
                message: 'Payment processing initiated , Check status using reference ID'
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

    public function callBack(array $payload): PaymentResourceInterface
    {
        $sessionId = $payload['session_id'] ?? null;
        $paymentStatus = $payload['payment_status'] ?? null;

        if (!$sessionId) {
            Log::error('Stripe callback: Missing session_id');
            return new IntializePaymentFailedResource();
        }

        $transaction = Transaction::where('gateway_response->session_id', $sessionId)->first();

        if (!$transaction) {
            Log::error('Stripe callback: Transaction not found', ['session_id' => $sessionId]);
            return new IntializePaymentFailedResource();
        }

        $status = $this->mapPaymentStatus($paymentStatus);

        $dto = new UpdateTransactionDto(
            id: $transaction->id,
            status: $status->value,
            gateway_response: array_merge(
                $transaction->gateway_response ?? [],
                [
                    'callback_received_at' => now()->toDateTimeString(),
                    'payment_status' => $paymentStatus,
                ]
            )
        );

        return (new UpdateTransactionAction())->execute($dto);
    }

    private function mapPaymentStatus(string $paymentStatus): Status
    {
        return match ($paymentStatus) {
            'paid' => Status::SUCCESS,
            'unpaid' => Status::PENDING,
            'failed' => Status::FAILED,
            default => Status::PROCESSING,
        };
    }

    public function getGatewayName(): string
    {
        return Gateway::STRIPE->value;
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
