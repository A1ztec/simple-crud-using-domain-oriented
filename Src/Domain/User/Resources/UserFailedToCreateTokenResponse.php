<?php

namespace Domain\User\Resources;

use Domain\User\Resources\Contracts\UserResourceInterface;

class UserFailedToCreateTokenResponse implements UserResourceInterface
{
    public function getCode(): int
    {
        return 500;
    }

    public function getMessage(): string
    {
        return 'Failed to create user token';
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function getData(): mixed
    {
        return null;
    }
}
