<?php


namespace Domain\Product\Resources;

use Domain\Product\Resources\Contracts\ProductResourceInterface;


class UpdateProductSuccessResource implements ProductResourceInterface
{
    public function getCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return 'Product updated successfully';
    }

    public function isSuccess(): bool
    {
        return true;
    }

    public function getData(): mixed
    {
        return null;
    }
}
