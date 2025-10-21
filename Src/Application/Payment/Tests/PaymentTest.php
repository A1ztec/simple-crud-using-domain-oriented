<?php

namespace Application\Payment\Tests;

use Tests\TestCase;
use Domain\User\Models\User;
use Domain\Payment\Enums\StatusEnum;
use Domain\Payment\Enums\GatewayEnum;
use Domain\Payment\Models\Transaction;
use Domain\Payment\Jobs\GatewayPaymentProcess;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        $this->token = JWTAuth::fromUser($this->user);
    }


    private function authenticatedJson($method, $uri, array $data = [])
    {
        return $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->json($method, $uri, $data);
    }

    #[test]
    public function user_can_create_payment_with_cod()
    {

        $response = $this->authenticatedJson('POST', '/api/v1/payments/pay', [
            'amount' => 1000,
            'gateway' => 'cod'
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "id",
                "type",
                "attributes" => [
                    "user_id",
                    "amount",
                    "status",
                    "gateway",
                    "reference_id",
                    "metadata",
                    "gateway_response"
                ]
            ],
            "meta" => [
                "success",
                "code",
                "message",
            ],
        ]);

        $response->assertJson([
            'meta' => [
                'success' => true,
                'code' => 200,
            ]
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => 1000,
            'gateway' => GatewayEnum::COD,
            'status' => StatusEnum::SUCCESS,
        ]);

        $transaction = Transaction::where('user_id', $this->user->id)->first();
        $this->assertNotNull($transaction->metadata);
        $this->assertNotNull($transaction->gateway_response);
        $this->assertEquals('Cash on Delivery', $transaction->metadata['payment_method']);
    }

    #[test]
    public function user_can_create_payment_with_online_gateway()
    {
        Queue::fake();


        $response = $this->authenticatedJson('POST', '/api/v1/payments/pay', [
            'amount' => 1000,
            'gateway' => 'stripe'
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "id",
                "type",
                "attributes" => [
                    "user_id",
                    "amount",
                    "status",
                    "gateway",
                    "reference_id",
                    "metadata",
                    "gateway_response"
                ]
            ],
            "meta" => [
                "success",
                "code",
                "message",
            ],
        ]);

        $response->assertJson([
            'meta' => [
                'success' => true,
                'message' => 'Payment processing initiated , Check status using reference ID',
            ]
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => 1000,
            'gateway' => GatewayEnum::STRIPE,
            'status' => StatusEnum::PENDING,
        ]);

        Queue::assertPushed(GatewayPaymentProcess::class, function ($job) {
            return $job->delay !== null && $job->queue === 'payment';
        });
    }

    #[test]
    public function user_cannot_create_payment_with_invalid_gateway()
    {

        $response = $this->authenticatedJson('POST', '/api/v1/payments/pay', [
            'amount' => 1000,
            'gateway' => 'invalid_gateway'
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'gateway'
            ]
        ]);

        $this->assertDatabaseMissing('transactions', [
            'user_id' => $this->user->id,
            'amount' => 1000,
        ]);
    }

    #[test]
    public function user_cannot_create_payment_without_authentication()
    {

        $response = $this->postJson('/api/v1/payments/pay', [
            'amount' => 1000,
            'gateway' => 'stripe'
        ]);

        $response->assertStatus(401);
    }

    #[test]
    public function user_cannot_create_payment_with_negative_amount()
    {

        $response = $this->authenticatedJson('POST', '/api/v1/payments/pay', [
            'amount' => -100,
            'gateway' => 'stripe'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
    }

    #[test]
    public function user_can_check_transaction_by_reference_id()
    {

        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'amount' => 1000,
            'gateway' => GatewayEnum::STRIPE,
            'status' => StatusEnum::SUCCESS,
            'reference_id' => 'STRIPE_TEST123',
            'metadata' => ['test' => 'data'],
            'gateway_response' => ['status' => 'succeeded'],
        ]);


        $response = $this->authenticatedJson('POST', '/api/v1/payments/check-transaction', [
            'reference_id' => 'STRIPE_TEST123'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'user_id',
                    'amount',
                    'status',
                    'gateway',
                    'reference_id',
                ]
            ],
            'meta' => [
                'success',
                'message',
                'code'
            ]
        ]);

        $response->assertJson([
            'data' => [
                'id' => (string)$transaction->id,
                'attributes' => [
                    'reference_id' => 'STRIPE_TEST123',
                ]
            ],
            'meta' => [
                'success' => true,
            ]
        ]);
    }
}
