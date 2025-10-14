<?php

namespace Application\Payment\Controllers\Api;

use Domain\Payment\Actions\CreateTransactionAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Application\Payment\Requests\CreatePaymentRequest;



class PaymentController
{
    public function pay(CreatePaymentRequest $request, CreateTransactionAction $action)
    {
        $data = $request->validated();
        $dto = new CreateTransactionDto(...$data);
        $resource = $action->execute($dto);
    }
}
