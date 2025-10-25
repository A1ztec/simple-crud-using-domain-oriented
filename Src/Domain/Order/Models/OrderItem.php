<?php

namespace Domain\Order\Models;

use Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
    protected $fillable = [
        'order_uuid',
        'product_id',
        'product_name',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
        'order_uuid' => 'string',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }
}
