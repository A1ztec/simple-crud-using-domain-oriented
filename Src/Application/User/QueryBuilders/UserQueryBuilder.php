<?php


namespace Application\User\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;


class UserQueryBuilder extends Builder

{
    public function whereEmail(string $email): self
    {
        return $this->where('email', $email);
    }

    public function whereVerificationCodeValid(): self
    {
        return $this->where('verification_code_expires_at', '>', now());
    }

    public function whereIsVerified(): self
    {
        return $this->whereNotNull('email_verified_at')
            ->whereNull('verification_code');
    }

    public function whereNotVerified(): self
    {
        return $this->where('email_verified_at', null);
    }
    public function whereVerificationCode(string $code): self
    {
        return $this->where('verification_code', $code);
    }
}
