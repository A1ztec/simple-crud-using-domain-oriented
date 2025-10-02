<?php


namespace Application\Product\ViewModels;

use Domain\Product\Models\Product;
use Domain\Product\DataObjects\ProductData;
use League\Fractal\Serializer\JsonApiSerializer;
use Domain\Product\QueryBuilder\ProductQueryBuilder;
use Application\Product\Transformers\ProductTransformer;


class ProductShowViewModel
{
    public function __construct(private ProductData $data) {}

    public function toResponse()
    {


        $data = $this->getData();

        return  fractal()->item($data)
            ->serializeWith(new JsonApiSerializer())
            ->transformWith(new ProductTransformer())
            ->toArray();
    }


    public function getData(): Product
    {
        return (new ProductQueryBuilder())->showProduct($this->data->id);
    }
}
