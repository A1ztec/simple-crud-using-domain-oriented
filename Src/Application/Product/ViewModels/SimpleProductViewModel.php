<?php

namespace Application\Product\ViewModels;

use Domain\Product\Resources\Contracts\ProductResourceInterface;

class SimpleProductViewModel
{
    public function __construct(private ProductResourceInterface $resource) {}

    public function toResponse()
    {
        return response()->json([
            'success' => $this->resource->isSuccess(),
            'message' => $this->resource->getMessage(),
            'code' => $this->resource->getCode(),
            'data' => $this->resource->getData()
        ], $this->resource->getCode());
    }
}
