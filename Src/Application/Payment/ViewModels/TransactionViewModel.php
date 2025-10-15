<?php

namespace Application\Payment\ViewModels;

use League\Fractal\Serializer\JsonApiSerializer;
use Application\payment\Transformers\TransactionTransformer;
use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Support\Traits\apiResponse;

class TransactionViewModel
{
    use apiResponse;
    public function __construct() {}

    public function toResponse(PaymentResourceInterface $resource): mixed
    {
        $isSuccess = $resource->isSuccess();

        if (!$isSuccess) {
            return $this->errorResponse(message: $resource->getMessage(), code: $resource->getCode());
        }

        $transaction = $resource->getData();

        if (!$resource->getData() && $isSuccess) {
            return $this->successResponse(message: $resource->getMessage(), code: $resource->getCode());
        }

        return fractal()->item($transaction)
            ->transformWith(new TransactionTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->addMeta([
                'success' => $resource->isSuccess(),
                'message' => $resource->getMessage(),
                'code' => $resource->getCode()
            ])
            ->toArray();
    }
}
