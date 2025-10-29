<?php

namespace Domain\Order\Actions;


use Application\Order\QueryBuilders\OrderQueryBuilder;
use Domain\Order\Resources\CheckForPendingOrderFailedResource;
use Domain\Order\Resources\CheckForPendingOrderSuccessResource;
use Domain\Order\Resources\ProcessPendingOrderFailedResource;
use Domain\Order\Resources\ProcessPendingOrderSuccessResource;

class ProcessPendingOrderAction
{
    public function __invoke($dto)
    {
        $existingOrder = (new OrderQueryBuilder())->pendingOrder();

        if (!$existingOrder) {
            return new ProcessPendingOrderFailedResource();
        }

        $stockValid = (new ValidateOrderStockAction())($existingOrder);
        if (!$stockValid->isSuccess()) {
            return $stockValid;
        }

        $resource = (new InitializeOrderPaymentAction())($existingOrder, $dto->gateway);
        if (!$resource->isSuccess()) {
            return $resource;
        }
        $data = ['transaction' => $resource->getData()['transaction']];
        
        return new ProcessPendingOrderSuccessResource(data: $data);
    }
}
