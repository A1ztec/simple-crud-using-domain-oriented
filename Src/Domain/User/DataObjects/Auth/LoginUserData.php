<?php

namespace Domain\User\DataObjects\Auth;


class LoginUserData
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
