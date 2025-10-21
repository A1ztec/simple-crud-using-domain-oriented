<?php

namespace Application\payment\Transformers;

use Domain\Payment\Models\Transaction;
use League\Fractal\TransformerAbstract;


class TransactionTransformer extends TransformerAbstract
{
    public function transform(?Transaction $transaction): array
    {
        if (!$transaction) {
            return [];
        }

        return [
            'id' => $transaction->id,
            'user_id' => $transaction->user_id,
            'amount' => $transaction->amount,
            'status' => $transaction->status->value ?? $transaction->status,
            'gateway' => $transaction->gateway->value ?? $transaction->gateway,
            'reference_id' => $transaction->reference_id,
        ];
    }
}
