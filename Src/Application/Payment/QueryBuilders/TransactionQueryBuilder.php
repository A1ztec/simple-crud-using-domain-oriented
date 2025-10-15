<?php

namespace Application\Payment\QueryBuilders;

use Domain\Payment\Models\Transaction;

class TransactionQueryBuilder
{
    public function getTransactionById(int $id): ?Transaction
    {
        return Transaction::with('user')->find($id);
    }
}
