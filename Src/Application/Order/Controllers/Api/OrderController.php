<?php


namespace Application\Order\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $data = $request->validated();
        $data['items'] = array_map(function ($item) {
            return new CreateOrderItemDto(productId: $item['product_id'], quantity: $item['quantity'], price: $item['price']);
        }, $data['items']);

        $dto = new CreateOrderDto(...$data);
        return (new OrderViewModel())->toResponse($action($dto));
    }
}
