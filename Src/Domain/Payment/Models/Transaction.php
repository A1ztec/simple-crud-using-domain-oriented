<?php

namespace Domain\Payment\Models;

use Domain\User\Models\User;
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
}
