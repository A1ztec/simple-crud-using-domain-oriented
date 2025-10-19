<?php

namespace Application\Payment\QueryBuilders;

use Domain\Payment\Models\Transaction;
use Illuminate\Support\Facades\Schema;

class TransactionQueryBuilder
{
    public function getTransactionByReferenceId(string $referenceId): ?Transaction
    {
        $columns = Schema::getColumnListing('transactions');
        $diffColumns = ['created_at', 'updated_at'];
        $selectColumns = array_diff($columns, $diffColumns);
        return Transaction::select($selectColumns)->where('reference_id', $referenceId)->first();
    }
}
