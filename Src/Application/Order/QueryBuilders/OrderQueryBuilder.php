<?php


namespace Application\Order\QueryBuilders;

use Domain\Order\Models\Order;
use Illuminate\Support\Facades\Auth;
use Domain\Order\Enums\OrderStatusEnum;


class OrderQueryBuilder
{

    public function pendingOrder()
    {
        return Order::where('user_id', Auth::id())
            ->where('status', OrderStatusEnum::PENDING)
            ->first();
    }
}
