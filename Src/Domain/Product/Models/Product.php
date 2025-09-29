<?php

namespace Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
}
