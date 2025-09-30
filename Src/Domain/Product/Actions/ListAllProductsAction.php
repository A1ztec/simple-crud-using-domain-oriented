<?php


namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Domain\Product\Resources\ProductResource;


class ListAllProductsAction
{

    public function execute()   : ProductResource
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return ProductResource::error(message: "No products found", code: 404);
        }
        return ProductResource::success(data: $products, message: "Products retrieved successfully", code: 200);
    }
}
