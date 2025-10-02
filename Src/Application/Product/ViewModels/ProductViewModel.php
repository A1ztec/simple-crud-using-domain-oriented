<?php

namespace Application\Product\ViewModels;

use League\Fractal\Serializer\JsonApiSerializer;
use Application\Product\Transformers\ProductTransformer;
use Src\Domain\Product\Resources\Contracts\ProductResourceInterface;

class ProductViewModel
{
    public function __construct(private ProductResourceInterface $resource) {}

    public function toResponse()
    {
        return fractal()->item($this->resource->getData())
            ->serializeWith(new JsonApiSerializer())
            ->transformWith(new ProductTransformer())
            ->addMeta([
                'success' => $this->resource->isSuccess(),
                'message' => $this->resource->getMessage(),
                'code' => $this->resource->getCode()
            ])
            ->toArray();
    }
}
