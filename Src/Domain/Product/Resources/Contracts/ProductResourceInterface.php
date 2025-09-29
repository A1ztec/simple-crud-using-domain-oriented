<?php

namespace Domain\Product\Resources\Contracts;



interface ProductResourceInterface
{

    public function getMessage(): string;

    public function getStatusCode(): int;


    public function getStatus() : bool;
}
