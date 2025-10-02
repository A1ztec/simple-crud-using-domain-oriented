<?php


namespace Domain\Product\Resources\Contracts;


interface ProductResourceInterface
{
    public function getCode(): int;

    public function getMessage(): string;

    public function isSuccess(): bool;

    public function getData(): mixed;
}
