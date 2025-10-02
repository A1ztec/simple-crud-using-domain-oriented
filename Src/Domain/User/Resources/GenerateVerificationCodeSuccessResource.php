<?php


namespace Domain\User\Resources;


use Domain\User\Models\User;
use Domain\User\Resources\Contracts\UserResourceInterface;


class GenerateVerificationCodeSuccessResource implements UserResourceInterface
{

    public function __construct(private User $user) {}

    public function getCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return 'Verification code generated successfully';
    }

    public function isSuccess(): bool
    {
        return true;
    }

    public function getData(): User
    {
        return $this->user;
    }
}
