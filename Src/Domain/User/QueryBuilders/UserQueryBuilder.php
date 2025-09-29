<?php


namespace Domain\User\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;


class UserQueryBuilder extends Builder

{
    public function whereEmail(string $email): self
    {
        return $this->where('email', $email);
    }

    public function whereVerificationCodeValid(): self
    {
        return $this->where('verification_code_expires_at', '>', now())
            ->whereNotNull('verification_code');
    }

    public function whereIsVerified(): self
    {
        return $this->whereNotNull('email_verified_at')
            ->whereNull('verification_code');
    }
}
