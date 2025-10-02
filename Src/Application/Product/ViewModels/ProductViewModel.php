<?php

namespace Application\Product\ViewModels;

use Domain\Product\QueryBuilder\ProductQueryBuilder;
use Support\Traits\apiResponse;
use Domain\Product\Resources\ProductResource;
use League\Fractal\Serializer\JsonApiSerializer;
use Application\Product\Transformers\ProductTransformer;
use Domain\Product\Models\Product;
use Domain\User\Resources\Contracts\UserResourceInterface;

class ProductViewModel
{
    use apiResponse;

    public function __construct( private UserResourceInterface $resource) {}

    public function toResponse()
    {


       $data = $this->resource->getData();

        return  fractal()->item($data)
            ->serializeWith(new JsonApiSerializer())
            ->transformWith(new ProductTransformer())
            ->toArray();
    }


    
}
