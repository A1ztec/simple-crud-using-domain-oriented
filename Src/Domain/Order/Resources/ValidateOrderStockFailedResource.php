<?php


namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;


class ValidateOrderStockFailedResource implements OrderResourceInterface
{

    public function getCode(): int
    {
        return 400;
    }
    public function isSuccess(): bool
    {
        return false;
    }
    public function getMessage(): string
    {
        return trans('One_or_more_products_are_out_of_stock');
    }
    public function getData(): ?array
    {
        return [];
    }
}
