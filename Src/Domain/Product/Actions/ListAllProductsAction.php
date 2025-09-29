<?php


namespace Domain\Product\Actions;

use Domain\Product\Models\Product;


class ListAllProductsAction
{

    public function execute()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            throw new \Exception("No products found");
        }
        return $products;
    }
}
