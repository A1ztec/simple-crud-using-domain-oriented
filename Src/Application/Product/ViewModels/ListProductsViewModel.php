<?php

namespace Application\Product\ViewModels;

use League\Fractal\Serializer\JsonApiSerializer;
use Application\Product\Transformers\ProductTransformer;
use Application\Product\QueryBuilders\ProductQueryBuilder;

class ListProductsViewModel
{
    public function toResponse()
    {
        $products = (new ProductQueryBuilder())->listAll();

        return fractal()->collection($products)
            ->transformWith(new ProductTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->toArray();
    }
}
