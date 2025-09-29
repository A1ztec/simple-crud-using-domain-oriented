<?php


namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Domain\Product\Resources\ProductResource;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;

class ShowOneProductAction
{

    public function execute(ShowOrDeleteOneProductData $dto): ProductResource
    {

        $product = Product::query()->whereId($dto->id)->first();

        if (!$product) {
            return ProductResource::error(message: "Product not found", code: 404);
        }

        return ProductResource::success(data: $product, message: "Product retrieved successfully", code: 200);
    }
}
