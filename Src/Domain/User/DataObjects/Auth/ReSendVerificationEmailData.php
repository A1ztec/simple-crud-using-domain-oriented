<?php

namespace Domain\User\DataObjects\Auth;

class ReSendVerificationEmailData
{
    public function __construct(
        public string $email
    ) {}
}
