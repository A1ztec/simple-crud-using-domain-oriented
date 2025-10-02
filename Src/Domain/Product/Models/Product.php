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
        'image',
    ];

    protected $casts = [
        'price' => 'float',
        'created_at' => 'datetime',
    ];

    
}
