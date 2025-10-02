<?php

namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Domain\User\Resources\UserResource;
use Domain\User\DataObjects\Auth\VerifyEmailData;
use Domain\User\Resources\UserVerifyEmailFailedResponse;
use Domain\User\Resources\UserVerifyEmailSuccessResponse;
use Domain\User\Resources\Contracts\UserResourceInterface;

class VerifyUserEmailAction
{
    public function execute(VerifyEmailData $data): UserResourceInterface
    {
        $user = User::query()->whereEmail($data->email)
            ->whereNotVerified()
            ->whereVerificationCode($data->verification_code)
            ->whereVerificationCodeValid()
            ->first();

        if (!$user) {
            return new UserVerifyEmailFailedResponse();
        }



        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();


        return (new UserVerifyEmailSuccessResponse(user: $user));
    }
}
