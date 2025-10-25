<?php

namespace Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\Product\QueryBuilder\ProductQueryBuilder;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'image',
    ];

    protected $casts = [
        'price' => 'float',
        'created_at' => 'datetime',
    ];
}
