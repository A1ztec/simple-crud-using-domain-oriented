<?php

namespace Application\Payment\ViewModels;

use League\Fractal\Serializer\JsonApiSerializer;
use Application\Payment\Transformers\TransactionTransformer;
use Domain\Payment\Models\Transaction;
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


        if ($data instanceof Transaction) {
            return fractal()->item($data)
                ->transformWith(new TransactionTransformer())
                ->serializeWith(new JsonApiSerializer())
                ->addMeta([
                    'success' => true,
                    'message' => $resource->getMessage(),
                    'code' => $resource->getCode()
                ])
                ->toArray();
        }


        return response()->json([
            'data' => $data,
            'meta' => [
                'success' => $resource->isSuccess(),
                'message' => $resource->getMessage(),
                'code' => $resource->getCode()
            ]
        ], $resource->getCode());
    }
}
