<?php

namespace Domain\Payment\DataObjects;

class HandleCallbackDto
{
    public function __construct(
        public readonly string $gateway,
        public readonly array $payload
    ) {}
}
