<?php


namespace Application\Order\Transformers;

use League\Fractal\TransformerAbstract;

class OrderItemsTransformer extends TransformerAbstract
{
    public function transform(mixed $data): array
    {
        return [
            'id' => $data->id,
            'order_uuid' => $data->order_uuid,
            'product_id' => $data->product_id,
            'product_name' => $data->product_name,
            'quantity' => $data->quantity,
            'price' => $data->price,
        ];
    }
}
