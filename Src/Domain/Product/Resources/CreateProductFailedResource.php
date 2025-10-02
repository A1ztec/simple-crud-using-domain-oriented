<?php


namespace Domain\Product\Resources;


use Src\Domain\Product\Resources\Contracts\ProductResourceInterface;

class CreateProductFailedResource implements ProductResourceInterface
{


    public function getCode(): int
    {
        return 400;
    }

    public function getMessage(): string
    {
        return 'Failed to create product';
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
