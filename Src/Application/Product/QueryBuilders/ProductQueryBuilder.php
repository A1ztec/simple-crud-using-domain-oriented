<?php

namespace Application\Product\QueryBuilders;

use Domain\Product\Models\Product;


class ProductQueryBuilder
{
    public function listAll()
    {
        return Product::all();
    }

    public function showProduct(int $id)
    {
        return Product::where('id', $id)->first() ?? null;
    }
}
