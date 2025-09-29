<?php

namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Domin\User\Resources\UserResource;

class VerifyUserEmailAction
{
    public function execute($data) : UserResource
    {
        $user = User::where('email', $data->email)->first();
        $code = $data->verification_code;

        if (!$user) {
            return UserResource::error(message: 'User not found', code: 404);
        }

        if ($user->hasVerifiedEmail()) {
            return UserResource::error(message: 'Email already verified', code: 400);
        }

        if ($user->verification_code_expires_at < now()) {
            return UserResource::error(message: 'Verification code expired', code: 400);
        }

        if ($user->verification_code != $code) {
            return UserResource::error(message: 'Invalid verification code', code: 400);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        return UserResource::success(data: [
            'user' => $user,
        ], code: 200, message: 'Email verified successfully');
    }
}
