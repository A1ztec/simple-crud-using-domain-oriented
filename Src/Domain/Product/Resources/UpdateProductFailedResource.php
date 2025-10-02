<?php


namespace Domain\Product\Resources;

use Src\Domain\Product\Resources\Contracts\ProductResourceInterface;


class UpdateProductFailedResource implements ProductResourceInterface
{
    public function getCode(): int
    {
        return 500;
    }

    public function getMessage(): string
    {
        return 'Failed to update product';
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function getData(): mixed
    {
        return null;
    }
}
