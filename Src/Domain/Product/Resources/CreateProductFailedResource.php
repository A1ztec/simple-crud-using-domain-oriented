<?php

namespace Domain\Product\Resources;

use Domain\Product\Resources\Contracts\ProductResourceInterface;

class CreateProductFailedResource implements ProductResourceInterface
{
    public function __construct() {}

    public function getMessage(): string
    {
        return "Product creation failed";
    }

    public function getStatusCode(): int
    {
        return 400;
    }

    public function getStatus(): bool
    {
        return false;
    }
}
