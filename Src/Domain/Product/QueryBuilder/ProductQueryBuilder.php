<?php

namespace Domain\Product\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder extends Builder
{

    public function whereId(int $id)
    {
        return $this->where('id', $id);
    }
}
