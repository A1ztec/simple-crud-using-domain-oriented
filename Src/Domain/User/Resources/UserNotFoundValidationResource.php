<?php

namespace Domain\User\Resources;

use Domain\User\Resources\Contracts\UserResourceInterface;


class UserNotFoundValidationResource implements UserResourceInterface
{
    public function getCode(): int
    {
        return 404;
    }

    public function getMessage(): string
    {
        return 'User not found';
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
