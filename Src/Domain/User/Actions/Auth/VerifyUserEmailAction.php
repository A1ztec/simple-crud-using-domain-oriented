<?php

namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Domin\User\Resources\UserResource;

class VerifyUserEmailAction
{
    public function execute($data): UserResource
    {
        $user = User::query()->whereEmail($data->email)
            ->whereNotVerified()
            ->whereVerificationCode($data->verification_code)
            ->whereVerificationCodeValid()
            ->first();

        if (!$user) {
            return UserResource::error(message: 'Invalid or expired verification code', code: 400);
        }



        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();


        return UserResource::success(data: [
            'user' => $user,
        ], code: 200, message: 'Email verified successfully');
    }
}
