<?php

namespace Application\Payment\ViewModels;

use League\Fractal\Serializer\JsonApiSerializer;
use Application\payment\Transformers\TransactionTransformer;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Support\Traits\apiResponse;

class TransactionViewModel
{
    use apiResponse;

    public function toResponse(PaymentResourceInterface $resource): mixed
    {
        if (!$resource->isSuccess()) {
            return $this->errorResponse(
                message: $resource->getMessage(),
                code: $resource->getCode()
            );
        }

        $data = $resource->getData();

        if (!$data) {
            return $this->successResponse(
                message: $resource->getMessage(),
                code: $resource->getCode()
            );
        }

        return response()->json([
            'data' => fractal()->item($data)
                ->transformWith(new TransactionTransformer())
                ->serializeWith(new JsonApiSerializer())
                ->toArray()['data'] ?? $data,
            'meta' => [
                'success' => true,
                'message' => $resource->getMessage(),
                'code' => $resource->getCode()
            ]
        ], $resource->getCode());
    }
}
