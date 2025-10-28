<?php

namespace Application\Product\ViewModels;

use League\Fractal\Serializer\JsonApiSerializer;
use Application\Product\Transformers\ProductTransformer;
use Domain\Product\Resources\Contracts\ProductResourceInterface;

class ProductViewModel
{


    public function toResponse(ProductResourceInterface $resource)
    {
        return fractal()->item($resource->getData())
            ->serializeWith(new JsonApiSerializer())
            ->transformWith(new ProductTransformer())
            ->addMeta([
                'success' => $resource->isSuccess(),
                'message' => $resource->getMessage(),
                'code' => $resource->getCode()
            ])
            ->toArray();
    }
}
