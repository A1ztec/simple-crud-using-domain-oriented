<?php

namespace Domain\Payment\Models;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;


class StripePaymentTransaction extends Model
{

    protected $table = 'stripe_payment_transactions';

    protected $fillable = [
        'payment_id',
        'transaction_id',
        'status',
        'amount',
        'checkout_url',
        'gateway_response',
    ];

    protected function casts()
    {
        return [
            'gateway_response' => 'array',
        ];
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'payment_method_gateway');
    }
}
