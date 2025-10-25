<?php

namespace Domain\Order\Models;

use Domain\User\Models\User;
use Domain\Order\Models\OrderItem;
use Domain\Payment\Models\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUlids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'uuid',
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
        'paid_at',
    ];

    protected $casts = [
        'price' => 'float',
        'paid_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_uuid', 'uuid');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_uuid', 'uuid');
    }
}
