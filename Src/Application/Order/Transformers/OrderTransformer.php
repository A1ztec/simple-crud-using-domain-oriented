<?php


namespace Application\Order\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Application\Order\Transformers\OrderItemsTransformer;
use Application\payment\Transformers\TransactionTransformer;


class OrderTransformer extends TransformerAbstract
{

    protected array $availableIncludes = [
        'items',
        'transaction',
    ];

    public function transform(mixed $data): array
    {
        return [
            'id' => (string)$data->uuid,
            'user_id' => $data->user_id,
            'total_amount' => $data->total_amount,
            'status' => $data->status,
            'shipping_address' => $data->shipping_address,
            'paid_at' => $data->paid_at,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
        ];
    }

    public function includeItems(mixed $data): ?Collection
    {
        return $this->collection($data->items, new OrderItemsTransformer(), 'items');
    }

    public function includeTransaction(mixed $data): ?Item
    {
        return $this->item($data->transaction, new TransactionTransformer(), 'transaction');
    }
}
