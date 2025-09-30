<?php

namespace Application\Product\ViewModels;

use Support\Traits\apiResponse;
use Domain\Product\Resources\ProductResource;
use League\Fractal\Serializer\JsonApiSerializer;
use Application\Product\Transformers\ProductTransformer;

class ProductViewModel
{
    use apiResponse;

    public function __construct(private ProductResource $resource) {}

    public function toResponse()
    {
        if (!$this->resource->isSuccess() || $this->resource->getcode() >= 400) {
            return $this->errorResponse(data: $this->resource->getdata(), code: $this->resource->getcode(), message: $this->resource->getmessage());
        }

        if ($this->resource->isSuccess() && $this->resource->getData() == null) {
            return $this->successResponse(code: $this->resource->getcode(), message: $this->resource->getmessage());
        }

        return  fractal()->item($this->resource->getData())
            ->transformWith(new ProductTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->toArray();
    }
}
