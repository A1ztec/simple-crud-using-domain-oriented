<?php


namespace Domain\Payment\Actions;

use Exception;
use Domain\Payment\Models\Transaction;
use Domain\Payment\DataObjects\UpdateTransactionDto;

class UpdateTransactionAction

{
    public function execute(UpdateTransactionDto $data)
    {
        // Logic to update a transaction
        $data = (array) $data;
        try {
            Transaction::update(...$data);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
