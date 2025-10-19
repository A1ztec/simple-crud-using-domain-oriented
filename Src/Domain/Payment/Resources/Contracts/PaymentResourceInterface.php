<?php


namespace Domain\Payment\Resources\Contracts;

use Domain\Payment\Models\Transaction;


interface PaymentResourceInterface
{
    public function isSuccess(): bool;
    public function getCode(): int;
    public function getMessage(): string;
    public function getData(): array;
}
