<?php

namespace Domain\User\DataObjects\Auth;

class VerifyEmailData
{
    public function __construct(
        public string $email,
        public string $verification_code
    ) {}
}
