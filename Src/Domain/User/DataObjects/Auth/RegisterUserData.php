<?php

namespace Domain\User\DataObjects\Auth;

class RegisterUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone = null
    ) {}
}
