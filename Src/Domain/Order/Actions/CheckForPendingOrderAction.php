<?php

namespace Domain\Order\Actions;


use Application\Order\QueryBuilders\OrderQueryBuilder;
use Domain\Order\Resources\CheckForPendingOrderFailedResource;
use Domain\Order\Resources\CheckForPendingOrderSuccessResource;

class CheckForPendingOrderAction
{
    public function __invoke($dto)
    {
        $existingOrder = (new OrderQueryBuilder())->pendingOrder();

        if (!$existingOrder) {
            return new CheckForPendingOrderFailedResource();
        }
        $resource = (new HandleOrderTransactionAction())($existingOrder, $dto->gateway);
        if (!$resource->isSuccess()) {
            return $resource;
        }
        $data = ['transaction' => $resource->getData()['transaction']];
        return new CheckForPendingOrderSuccessResource(data: $data);
    }
}
