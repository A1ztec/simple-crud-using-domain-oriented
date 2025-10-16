<?php

namespace Application\Payment\ViewModels;

use Support\Traits\apiResponse;
use League\Fractal\Serializer\JsonApiSerializer;
use Application\payment\Transformers\TransactionTransformer;
use Application\Payment\QueryBuilders\TransactionQueryBuilder;

class TransactionShowViewModel
{
    use apiResponse;
    public function __construct(private string $referenceId) {}

    public function toResponse(): mixed
    {
        $transaction = $this->getData();

        if (!$transaction) {
            return $this->errorResponse('Transaction not found', 404);
        }

        return fractal()->item($transaction)
            ->transformWith(new TransactionTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->addMeta([
                'success' => true,
                'message' => 'Transaction retrieved successfully',
                'code' => 200
            ])
            ->toArray();
    }

    private function getData()
    {
        return (new TransactionQueryBuilder())->getTransactionByReferenceId($this->referenceId);
    }
}
