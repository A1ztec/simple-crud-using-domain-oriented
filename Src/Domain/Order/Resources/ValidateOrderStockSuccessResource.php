<?php

namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;


class ValidateOrderStockSuccessResource implements OrderResourceInterface
{

    public function getCode(): int
    {
        return 200;
    }
    public function isSuccess(): bool
    {
        return true;
    }
    public function getMessage(): string
    {
        return trans('All_products_are_in_stock');
    }

    public function getData(): array
    {
        return [];
    }
}
