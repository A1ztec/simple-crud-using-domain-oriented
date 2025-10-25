<?php


namespace Application\Order\ViewModels;

use League\Fractal\Serializer\JsonApiSerializer;

use Application\Order\Transformers\OrderTransformer;
use Domain\Order\Resources\Contracts\OrderResourceInterface;


class OrderViewModel
{
    public function toResponse(OrderResourceInterface $resource)
    {
        if ($resource->isSuccess() && isset($resource->getData()['order'])) {
            return fractal()
                ->item($resource->getData()['order'], new OrderTransformer(), 'orders')
                ->parseIncludes(['items', 'transaction'])
                ->serializeWith(new JsonApiSerializer())
                ->addMeta([
                    'success' => true,
                    'message' => $resource->getMessage(),
                    'code' => $resource->getCode()
                ])
                ->toArray();
        }

        return response()->json([
            'data' => $resource->getData(),
            'meta' => [
                'success' => $resource->isSuccess(),
                'message' => $resource->getMessage(),
                'code' => $resource->getCode()
            ]
        ], $resource->getCode());
    }
}
