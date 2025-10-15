<?php

namespace Domain\Payment\Models;

use Domain\User\Models\User;
use Domain\Payment\Enums\Status;
use Domain\Payment\Enums\Gateway;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'status',
        'amount',
        'gateway',
        'user_id',
        'gateway_response',
        'metadata'
    ];

    protected function casts()
    {
        return [
            'status' => Status::class,
            'gateway' => Gateway::class,
            'gateway_response' => 'array',
            'metadata' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
