<?php

namespace Application\Product\ViewModels;

use Domain\Product\Models\Product;
use League\Fractal\Serializer\JsonApiSerializer;
use Application\Product\Transformers\ProductTransformer;
use Application\Product\QueryBuilders\ProductQueryBuilder;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;

class ProductShowViewModel
{
    public function __construct(private ShowOrDeleteOneProductData $data) {}

    public function toResponse()
    {
        $product = $this->getData();

        return fractal()->item($product)
            ->serializeWith(new JsonApiSerializer())
            ->transformWith(new ProductTransformer())
            ->toArray();
    }

    private function getData(): Product
    {
        return (new ProductQueryBuilder())->showProduct($this->data->id);
    }
}
