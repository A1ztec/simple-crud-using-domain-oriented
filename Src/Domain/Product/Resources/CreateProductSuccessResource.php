<?php


namespace Domain\Product\Resources;

use Domain\Product\Models\Product;
use Domain\Product\Resources\Contracts\ProductResourceInterface;

class CreateProductSuccessResource implements ProductResourceInterface
{
    public function __construct(private Product $data) {}
    public function getCode(): int
    {
        return 201;
    }

    public function getMessage(): string
    {
        return 'Product created successfully';
    }

    public function isSuccess(): bool
    {
        return true;
    }

    public function getData(): Product
    {
        return $this->data;
    }
}
