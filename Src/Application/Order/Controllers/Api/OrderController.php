<?php


namespace Application\Order\Controllers\Api;


use Illuminate\Support\Facades\App;
use App\Http\Middleware\ChangeLanguage;
use Spatie\RouteAttributes\Attributes\Post;
use Domain\Order\DataObjects\CreateOrderDto;
use Spatie\RouteAttributes\Attributes\Prefix;
use Application\Order\ViewModels\OrderViewModel;
use Spatie\RouteAttributes\Attributes\Middleware;
use Application\Order\Requests\CreateOrderRequest;
use Domain\Order\Actions\InitializeOrderCheckoutAction;

#[Prefix('orders')]
#[Middleware(ChangeLanguage::class)]
class OrderController
{

    #[Post(
        uri: '/store',
        name: 'orders.store',
        middleware: ['permission:create_order']
    )]
    public function store(CreateOrderRequest $request, InitializeOrderCheckoutAction $action)
    {
        return (new OrderViewModel())->toResponse($action(CreateOrderDto::fromRequest($request->validated())));
    }
}
