<?php


namespace Domain\Payment\Actions;

use Exception;
use Domain\Payment\Enums\Status;
use Domain\Payment\Models\Transaction;
use Domain\Payment\DataObjects\CreateTransactionDto;

class CreateTransactionAction

{
    public function execute(CreateTransactionDto $data): mixed
    {
        // Logic to create a transaction


        $data->status = Status::PENDING->value;
        $data = (array) $data;
        // dd($data);
        try {
            $transaction = Transaction::create(...$data);
            return $transaction;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
