<?php

namespace Application\Payment\QueryBuilders;

use Domain\Payment\Models\Transaction;

class TransactionQueryBuilder
{
    public function getTransactionByReferenceId(string $referenceId): ?Transaction
    {
        return Transaction::with('user')->where('reference_id', $referenceId)->first();
    }
}
