<?php


namespace Domain\Product\Resources;

use Domain\Product\Resources\contracts\ProductResourceInterface;



class CreateProductSuccessResource implements ProductResourceInterface
{
    public function __construct(private mixed $data) {}


    public function getMessage(): string
    {
        return "Product created successfully";
    }

    public function getStatusCode(): int
    {
        return 201;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getStatus(): bool
    {
        return true;
    }
}
