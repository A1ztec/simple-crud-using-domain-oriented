<?php


namespace Domain\Product\Resources;

use Domain\Product\Models\Product;
use Src\Domain\Product\Resources\Contracts\ProductResourceInterface;


class DeleteProductSuccessResource implements ProductResourceInterface
{


    public function getCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return 'Product deleted successfully';
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
