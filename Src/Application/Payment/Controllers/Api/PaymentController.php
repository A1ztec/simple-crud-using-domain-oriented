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
use Domain\Payment\Models\Transaction;

#[Prefix('payments')]
class PaymentController
{
    #[Post(
        uri: '/pay',
        name: 'payments.pay'
    )]
    public function pay(CreatePaymentRequest $request, IntializePaymentAction $action) : TransactionViewModel
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        return (new TransactionViewModel())->toResponse($action->execute(new CreateTransactionDto(...$data)));
    }

    #[Post(
        uri: '/check-transaction',
        name: 'payments.check-transaction'
    )]

    public function checkTransaction(CheckTransactionRequest $request) : TransactionShowViewModel
    {
        $data = $request->validated();
        $dto = new ShowTransactionDto(...$data);
        return (new TransactionShowViewModel($dto->reference_id))->toResponse();
    }
}
