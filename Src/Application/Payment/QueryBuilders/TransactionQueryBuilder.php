<?php

namespace Application\Payment\QueryBuilders;

use Domain\Payment\Models\Transaction;
use Illuminate\Support\Facades\Schema;

class TransactionQueryBuilder
{
    public function getTransactionByReferenceId(string $referenceId): ?Transaction
    {
        return Transaction::where('reference_id', $referenceId)->first();
    }
}
