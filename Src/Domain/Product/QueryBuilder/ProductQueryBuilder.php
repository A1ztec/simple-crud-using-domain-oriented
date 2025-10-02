<?php

namespace Domain\Product\QueryBuilder;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;

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
