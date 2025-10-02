<?php

namespace Application\Product\ViewModels;

use Support\Traits\apiResponse;
use Domain\Product\Models\Product;
use Domain\Product\Resources\ProductResource;
use League\Fractal\Serializer\JsonApiSerializer;
use Domain\User\Resources\ListAllProductsQueryBuilder;
use Application\Product\Transformers\ProductTransformer;

class ListProductsViewModel
{
    use apiResponse;

    public function __construct(private mixed $products) {}

    public function toResponse()
    {

        return fractal()->collection($this->products)
            ->transformWith(new ProductTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->toArray();
    }
}
