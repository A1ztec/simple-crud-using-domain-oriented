<?php


namespace Application\Payment\Transformers;

use League\Fractal\TransformerAbstract;


class StripeTransactionTransformer extends TransformerAbstract
{
    public function transform($data)
    {
        return [
            'payment_id' => $data->payment_id,
            'amount' => $data->amount,
            'status' => $data->status,
            'transaction_id' => $data->transaction_id,
            'checkout_url' => $data->checkout_url,
        ];
    }
}
