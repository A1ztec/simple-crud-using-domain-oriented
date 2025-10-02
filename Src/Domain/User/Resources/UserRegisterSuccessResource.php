<?php

namespace Domain\User\Resources;

use Domain\User\Resources\Contracts\UserResourceInterface;

class UserRegisterSuccessResource implements UserResourceInterface
{
    public function __construct(private mixed $data) {}


    public function getCode(): int
    {
        return 201;
    }

    public function getMessage(): string
    {
        return 'User registered successfully';
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
