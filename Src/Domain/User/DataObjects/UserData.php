<?php

namespace Domain\User\DataObjects;


class UserData
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public ?string $phone,
        public ?string $email_verified_at,
        public ?int $verification_code,
        public string $password,
        public ?string $remember_token,
        public ?string $created_at,
        public ?string $updated_at,
    ) {}
}
