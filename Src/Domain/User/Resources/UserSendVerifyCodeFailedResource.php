<?php

namespace Domain\User\Resources;

use Domain\User\Resources\Contracts\UserResourceInterface;



class UserSendVerifyCodeFailedResource implements UserResourceInterface
{

    public function __construct(private ?string $message = null) {}

    public function getCode(): int
    {
        return 400;
    }

    public function getMessage(): string
    {
        return 'Failed to send verification code';
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
