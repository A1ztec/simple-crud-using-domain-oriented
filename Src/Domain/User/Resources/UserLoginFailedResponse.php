<?php

namespace Domain\User\Resources;

use Domain\User\Resources\Contracts\UserResourceInterface;

class UserLoginFailedResponse implements UserResourceInterface
{

    public function __construct(private ?string $message) {}
    public function getCode(): int
    {
        return 401;
    }

    public function getMessage(): string
    {
        return $this->message ?? 'User login failed';
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
