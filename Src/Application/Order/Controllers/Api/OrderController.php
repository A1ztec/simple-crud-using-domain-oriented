<?php


namespace Application\Order\Controllers\Api;

use Domain\Order\Actions\CreateOrderAction;
use Spatie\RouteAttributes\Attributes\Post;
use Domain\Order\DataObjects\CreateOrderDto;
use Spatie\RouteAttributes\Attributes\Prefix;
use Application\Order\ViewModels\OrderViewModel;
use Domain\Order\DataObjects\CreateOrderItemDto;
use Application\Order\Requests\CreateOrderRequest;

#[Prefix('orders')]
class OrderController
{

    #[Post(
        uri: '/',
        name: 'orders.store',
        middleware: ['permission:create_order']
    )]
    public function store(CreateOrderRequest $request, CreateOrderAction $action)
    {
        return (new OrderViewModel())->toResponse($action(CreateOrderDto::fromRequest($request->validated())));
    }
}
