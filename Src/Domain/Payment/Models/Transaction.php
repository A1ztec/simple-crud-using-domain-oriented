<?php

namespace Domain\Payment\Models;

use Domain\User\Models\User;
use Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'status',
        'amount',
        'gateway',
        'user_id',
        'reference_id',
        'order_uuid',
        'payment_method_gateway_id',
        'payment_method_gateway_type',
    ];



    public function paymentMethodGateway(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }
}
