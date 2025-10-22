<?php

namespace Domain\Payment\Gateways;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\Payment\Enums\StatusEnum;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Domain\Payment\Enums\GatewayEnum;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Models\StripePaymentTransaction;
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

    public function processPayment(Transaction $transaction): PaymentResourceInterface
    {

        $stripeTransaction = StripePaymentTransaction::create([
            'payment_id' => $transaction->id,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
        ]);

        $response = $this->makeRequest('/v1/checkout/sessions', $this->formatData($transaction));

        if ($response->successful()) {
            $responseData = $response->json();


            $stripeTransaction->update(['transaction_id' => $responseData['id'], 'checkout_url' => $responseData['url']]);
            $transaction->paymentMethodGateway()->associate($stripeTransaction)->save();


            return new IntializePaymentSuccessResource(
                data: [
                    'checkout_url' => $responseData['url'],
                    'session_id' => $responseData['id'],
                    'reference_id' => $transaction->reference_id,
                ],
                message: 'Payment processing initiated , Check status using reference ID'
            );
        }
        Log::channel('payment')->error('Stripe payment initiation failed', ['response_status' => $response->status(), 'response_body' => $response->body()]);
        return new IntializePaymentFailedResource();
    }




    public function callBack(array $payload): PaymentResourceInterface
    {
        $sessionId = $payload['session_id'];
        $paymentStatus = $payload['payment_status'];

        $stripeTransaction = StripePaymentTransaction::where('transaction_id', $sessionId)->with('transaction')->first();


        if (!$stripeTransaction) return new IntializePaymentFailedResource(message: 'Transaction not Found for this session_id');

        $status = StatusEnum::stripeStatus($paymentStatus);

        DB::transaction(function () use ($stripeTransaction, $payload, $status) {

            $stripeTransaction->update(['gateway_response' => $payload, 'status' => $status]);
            $stripeTransaction->transaction->update(['status' => $status]);
        });

        return new IntializePaymentSuccessResource(
            data: [
                'reference_id' => $stripeTransaction->transaction->reference_id,
                'status' => $status,
            ],
            message: 'Stripe Payment Proccessed Successfully'
        );
    }



    public function getGatewayName(): string
    {
        return GatewayEnum::STRIPE;
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
