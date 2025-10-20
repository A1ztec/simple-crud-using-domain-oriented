<?php

namespace Domain\Payment\Gateways;

use Exception;
use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Contracts\PaymentGatewayInterface;
use Domain\Payment\Contracts\OnlinePaymentGatewayInterface;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;

class StripeGateway implements PaymentGatewayInterface, OnlinePaymentGatewayInterface
{
    private string $baseUrl;
    private string $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('services.stripe.base_url');
        $this->secretKey = config('services.stripe.secret_key');
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

        try {
            $response = $this->makeRequest('/v1/checkout/sessions', $this->formatData($transaction));

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['id'])) {
                    $transaction->update([
                        'status' => Status::PENDING,
                        'gateway_response' => [
                            'gateway' => $this->getGatewayName(),
                            'session_id' => $responseData['id'],
                            'initiated_at' => now()->toDateTimeString(),
                        ]
                    ]);

                    return new IntializePaymentSuccessResource(
                        data: [
                            'checkout_url' => $responseData['url'] ?? null,
                            'session_id' => $responseData['id'],
                            'status' => $responseData['status'] ?? 'open',
                        ],
                        message: 'Payment processing initiated , Check status using reference ID'
                    );
                }
            }
            return new IntializePaymentFailedResource();
        } catch (Exception $e) {
            Log::error('Stripe payment initialization failed', [
                'response' => $response->json(),
                'status' => $response->status(),
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

        $status = $this->mapStatus($paymentStatus);

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

    private function mapStatus(string $paymentStatus): Status
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

    public function formatData($transaction): array
    {
        return [
            'success_url' => url('api/v1/payments/success?session_id={CHECKOUT_SESSION_ID}'), // to redirect user for specific url not the webhook
            'client_reference_id' => $transaction->reference_id,
            'mode' => 'payment',
            'line_items[0][price_data][currency]' => 'usd',
            'line_items[0][price_data][product_data][name]' => 'Payment for order ' . $transaction->reference_id,
            'line_items[0][price_data][unit_amount]' => (int)($transaction->amount * 100),
            'line_items[0][quantity]' => 1,
        ];
    }

   
    public function makeRequest(string $endpoint, array $data = []): Response
    {
        $url = $this->baseUrl . $endpoint;

        return Http::withHeader('Authorization', 'Bearer ' . $this->secretKey)
            ->asForm()
            ->post($url, $data);
    }
}
