<?php

namespace Domain\User\Resources;

use Domain\User\Resources\Contracts\UserResourceInterface;


class UserLoginSuccessResponse implements UserResourceInterface
{

    public function __construct(private mixed $data) {}
    public function getCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return 'User logged in successfully';
    }

    public function isSuccess(): bool
    {
        return true;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
