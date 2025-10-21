<?php

namespace Application\Payment\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Domain\Payment\Models\Transaction;
use Spatie\RouteAttributes\Attributes\Post;
use Illuminate\Database\Eloquent\Casts\Json;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Payment\DataObjects\HandleCallbackDto;
use Domain\Payment\Actions\IntializePaymentAction;
use Domain\Payment\DataObjects\ShowTransactionDto;
use Domain\Payment\Actions\CreateTransactionAction;
use Domain\Payment\DataObjects\CreateTransactionDto;
use Domain\Payment\DataObjects\UpdateTransactionDto;
use Application\Payment\Requests\CreatePaymentRequest;
use Domain\Payment\Actions\HandlePaymentCallbackAction;
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
    public function pay(CreatePaymentRequest $request, IntializePaymentAction $action): JsonResponse|array
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        return (new TransactionViewModel())->toResponse($action(new CreateTransactionDto(...$data)));
    }

    #[Post(
        uri: '/check-transaction',
        name: 'payments.check-transaction'
    )]

    public function checkTransaction(CheckTransactionRequest $request): JsonResponse|array
    {
        $data = $request->validated();
        $dto = new ShowTransactionDto(...$data);
        return (new TransactionShowViewModel())->toResponse($dto->reference_id);
    }

    // toDo : implement gateway callback handling

    #[Post(
        uri: '/callback',
        name: 'payments.callback',
        withoutMiddleware: ['jwt.auth']
    )]
    public function callback(GatewayCallbackRequest $request, HandlePaymentCallbackAction $action): JsonResponse|array
    {
        $data = $request->validated();
        $dto = new HandleCallbackDto(...$data);
        return (new TransactionViewModel())->toResponse($action($dto));
    }
}
