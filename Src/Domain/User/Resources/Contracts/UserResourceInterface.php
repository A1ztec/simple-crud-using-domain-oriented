<?php

namespace Domain\User\Resources\Contracts;

use Domain\User\Models\User;

interface UserResourceInterface
{
    public function getCode(): int;

    public function getMessage(): string;

    public function isSuccess(): bool;

    public function getData(): mixed;
}
