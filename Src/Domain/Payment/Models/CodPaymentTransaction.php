<?php

namespace Domain\Payment\Models;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;


class CodPaymentTransaction extends Model
{

    protected $table = 'cod_payment_transactions';

    protected $fillable = [
        'payment_id',
        'status',
        'amount',
    ];



    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'payment_method_gateway');
    }

}
