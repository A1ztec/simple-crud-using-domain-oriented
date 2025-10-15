<?php

namespace Application\Payment\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Payment\Actions\IntializePaymentAction;
use Domain\Payment\DataObjects\ShowTransactionDto;
use Domain\Payment\Actions\CreateTransactionAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Application\Payment\Requests\CreatePaymentRequest;
use Application\Payment\Requests\GatewayCallbackRequest;
use Application\Payment\ViewModels\TransactionViewModel;
use Application\Payment\Requests\CheckTransactionRequest;
use Application\Payment\ViewModels\TransactionShowViewModel;

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
        $data['user_id'] = Auth::id();
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
        $dto = new ShowTransactionDto(...$data);
        return (new TransactionShowViewModel($dto->id))->toResponse();
    }
}
