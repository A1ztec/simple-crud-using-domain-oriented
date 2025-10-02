<?php

namespace Domain\User\Resources;

use Domain\User\Models\User;
use Domain\User\Resources\Contracts\UserResourceInterface;


class UserVerifyEmailFailedResponse implements UserResourceInterface
{



    public function getCode(): int
    {
        return 400;
    }

    public function getMessage(): string
    {
        return 'Invalid or expired verification code';
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
