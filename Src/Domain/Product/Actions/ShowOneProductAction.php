<?php


namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;

class ShowOneProductAction
{

    public function execute(ShowOrDeleteOneProductData $dto)
    {

        $product = Product::query()->whereId($dto->id)->first();

        if (!$product) {
            throw new \Exception("Product not found");
        }
    }
}
