<?php

namespace Application\Payment\Controllers\Api;

use Application\Payment\Requests\CreatePaymentRequest;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Payment\Actions\IntializePaymentAction;
use Domain\Payment\Actions\CreateTransactionAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Application\Payment\Requests\GatewayCallbackRequest;
use Application\Payment\ViewModels\TransactionViewModel;
use Application\Payment\Requests\CheckTransactionRequest;
use Spatie\RouteAttributes\Attributes\Post;

#[Prefix('payments')]
class PaymentController
{
    #[Post(
        uri: '/pay',
        name: 'payments.pay'
    )]
    public function pay(CreatePaymentRequest $request, IntializePaymentAction $action)
    {
        $data = $request->validated();
        $dto = new CreateTransactionDto(...$data);
        $resource = $action->execute($dto);
        return (new TransactionViewModel())->toResponse($resource);
    }

    #[Post(
        uri: '/check-transaction',
        name: 'payments.check-transaction'
    )]

    public function checkTransaction(CheckTransactionRequest $request)
    {
        $data = $request->validated();
        $dto = new UpdateTransactionDto(...$data);
        return (new TransactionViewModel())->toResponse($dto);
    }
}
