<!-- <?php

namespace Domain\Payment\Tests;

use Tests\TestCase;
use Domain\Payment\Models\Transaction;
use PHPUnit\Framework\Attributes\Test;
use Domain\Payment\Actions\IntializePaymentAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Payment\Actions\UpdateTransactionAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Domain\Payment\Resources\IntializePaymentFailedResource;
use Domain\Payment\Resources\IntializePaymentSuccessResource;
use Domain\Payment\Resources\UpdateTransactionFailedResource;
use Domain\Payment\Resources\UpdateTransactionSuccessResource;

class PaymentModuleDomainTest extends TestCase
{
    // use RefreshDatabase;

    // #[Test]
    // public function test_Update_Transaction_Returns_Failed_Resource()
    // {
    //     $dto = new UpdateTransactionDto(id: 9999);
    //     $resource = (new UpdateTransactionAction())($dto);
    //     $this->assertInstanceOf(UpdateTransactionFailedResource::class, $resource);
    //     $this->assertTrue(method_exists($resource, 'getMessage'));
    //     $this->assertEquals(false, $resource->isSuccess());
    //     $this->assertEquals(400, $resource->getCode());
    //     $this->assertNotNull($resource->getMessage());
    //     $this->assertEmpty($resource->getData());
    // }

    // #[Test]
    // public function test_Update_Transaction_Returns_Success_Resource()
    // {
    //     $transaction = Transaction::create([
    //         'user_id' => 1,
    //         'amount' => 100,
    //         'status' => 'pending',
    //         'gateway' => 'stripe',
    //         'reference_id' => 'ref_' . uniqid(),
    //         'metadata' => [],
    //         'gateway_response' => [],
    //     ]);

    //     $data = collect($transaction)->except([
    //         'gateway',
    //         'user_id',
    //         'created_at',
    //         'updated_at',
    //     ])->toArray();
    //     $dto = new UpdateTransactionDto(...$data);
    //     $resource = (new UpdateTransactionAction())($dto);
    //     $this->assertInstanceOf(UpdateTransactionSuccessResource::class, $resource);
    //     $this->assertTrue(method_exists($resource, 'getMessage'));
    //     $this->assertEquals(true, $resource->isSuccess());
    //     $this->assertTrue(method_exists($resource, 'getData'));
    //     $this->assertEquals(200, $resource->getCode());
    //     $this->assertNotNull($resource->getMessage());
    //     $this->assertNotEmpty($resource->getData());
    // }

    // public function test_Intialize_Payment_Return_Successful_Resource()
    // {
    //     $dto = new CreateTransactionDto(amount: 100, gateway: 'stripe', user_id: 1);
    //     $resource = (new IntializePaymentAction())($dto);
    //     $this->assertInstanceOf(IntializePaymentSuccessResource::class, $resource);
    //     $this->assertTrue(method_exists($resource, 'getMessage'));
    //     $this->assertEquals(true, $resource->isSuccess());
    //     $this->assertTrue(method_exists($resource, 'getData'));
    //     $this->assertEquals(200, $resource->getCode());
    //     $this->assertNotNull($resource->getMessage());
    //     $this->assertNotEmpty($resource->getData());
    // } -->

    // public function test_Intialize_Payment_Return_Failed_Resource()
    // {
    //     $dto = new CreateTransactionDto(amount: -50, gateway: 'unknown_gateway', user_id: 1);
    //     $resource = (new IntializePaymentAction())->execute($dto);
    //     $this->assertInstanceOf(IntializePaymentFailedResource::class, $resource);
    //     $this->assertTrue(method_exists($resource, 'getMessage'));
    //     $this->assertEquals(false, $resource->isSuccess());
    //     $this->assertEquals(400, $resource->getCode());
    //     $this->assertNotNull($resource->getMessage());
    //     $this->assertEmpty($resource->getData());
    // }
}
