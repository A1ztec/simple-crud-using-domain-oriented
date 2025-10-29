<?php

namespace Domain\Order\Resources;

use Domain\Order\Resources\Contracts\OrderResourceInterface;

class DecrementProductStockResource implements OrderResourceInterface
{
    public function __construct(private array $data) {}

    public function isSuccess(): bool
    {
        return true;
    }

    public function getCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return trans('Product_stock_decremented_successfully');
    }

    public function getData(): array
    {
        return $this->data;
    }
}
