<?php

namespace Application\Product\ViewModels;

use Domain\Product\Resources\ProductResource;
use Support\Traits\apiResponse;

class ProductViewModel
{
    use apiResponse;

    public function __construct(private ProductResource $resource) {}

    public function toResponse()
    {
        if(!$this->resource->isSuccess()|| $this->resource->getcode() >= 400) {
            return $this->errorResponse(data: $this->resource->getdata(), code: $this->resource->getcode(), message: $this->resource->getmessage());
        }


    }
}
