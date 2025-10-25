<?php

namespace Domain\Order\Resources\Contracts;


interface OrderResourceInterface
{
    public function getMessage(): string;

    public function getData(): array|null;

    public function getCode(): int;

    public function isSuccess(): bool;
}
