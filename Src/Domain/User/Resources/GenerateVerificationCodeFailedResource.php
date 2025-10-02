<?php


namespace Domain\User\Resources;


use Domain\User\Resources\Contracts\UserResourceInterface;


class GenerateVerificationCodeFailedResource implements UserResourceInterface
{

    public function getCode(): int
    {
        return 400;
    }

    public function getMessage(): string
    {
        return 'Failed to generate verification code';
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
