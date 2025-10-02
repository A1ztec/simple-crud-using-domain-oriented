<?php

namespace Domain\User\Resources;

use Domain\User\Resources\Contracts\UserResourceInterface;



class UserSendVerifyCodeSuccessResource implements UserResourceInterface
{

    public function getCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return 'Verification code sent successfully';
    }

    public function isSuccess(): bool
    {
        return true;
    }

    public function getData(): mixed
    {
        return null;
    }
}
