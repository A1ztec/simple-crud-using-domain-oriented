<?php


namespace Application\Payment\Tests;

use Tests\TestCase;
use Domain\User\Models\User;
use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Domain\Payment\Jobs\GatewayPaymentProcess;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;


class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private $user;


    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email_verified_at' => now()
        ]);
    }

    #[test]
    public function user_can_create_payment_with_cod()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/payments', [
            'amount' => 1000,
            'gateway' => 'cod'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "type",
                "id",
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
                'message',
            ],
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => 1000,
            'gateway' => Gateway::COD->value,
            'status' => Status::PENDING->value,
        ]);
    }

    #[test]
    public function user_can_create_payment_with_online_gateway()
    {
        Queue::fake();

        $response = $this->actingAs($this->user)->postJson('/api/v1/payments', [
            'amount' => 1000,
            'gateway' => 'stripe'
        ]);


        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "type",
                "id",
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
                'message',
            ],
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => 1000,
            'gateway' => Gateway::STRIPE->value,
            'status' => Status::PENDING->value,
        ]);

        Queue::assertPushed(GatewayPaymentProcess::class, function ($job) {
            return $job->delay !== null && $job->queue === 'payments';
        });
    }

    #[test]
    public function user_cannot_create_payment_with_invalid_gateway()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/payments', [
            'amount' => 1000,
            'gateway' => 'invalid_gateway'
        ]);

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'code',
            'status',
            'message' => [
                'gateway'
            ],
            'data'
        ]);

        $this->assertDatabaseMissing('transactions', [
            'user_id' => $this->user->id,
            'amount' => 1000,
            'gateway' => Gateway::COD->value,
            'status' => Status::PENDING->value,
        ]);
    }
}
