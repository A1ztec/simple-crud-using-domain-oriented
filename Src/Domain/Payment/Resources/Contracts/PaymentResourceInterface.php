<?php

namespace Domain\Payment\Resources\Contracts;

interface PaymentResourceInterface
{
    public function isSuccess(): bool;

    public function getCode(): int;

    public function getMessage(): string;

    public function getData(): mixed;
}
