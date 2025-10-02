<?php


namespace Domain\User\Actions\Auth;

use Domain\User\DataObjects\Auth\LoginUserData;
use Domain\User\Models\User;
use Domain\User\Resources\Contracts\UserResourceInterface;
use Domain\User\Resources\UserFailedToCreateTokenResponse;
use Domain\User\Resources\UserLoginFailedResponse;
use Domain\User\Resources\UserLoginSuccessResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Domain\User\Resources\UserResource;

class LoginUserAction
{
    public function execute(LoginUserData $data): UserResourceInterface
    {
        $user = User::query()->whereEmail($data->email)->first();

        $validPassword = $user ? Hash::check($data->password, $user->password) : false;

        if (!$user || !$validPassword) {
            return (new UserLoginFailedResponse(message: 'Invalid credentials'));
        }

        if (!$user->hasVerifiedEmail()) {
            return (new UserLoginFailedResponse(message: 'Email not verified'));
        }

        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $e) {
            Log::error('Could not create token for user: ' . $e->getMessage());
            return (new UserFailedToCreateTokenResponse());
        }

        return (new UserLoginSuccessResponse(data: [
            'token' => $token,
            'user' => $user,
        ]));
    }
}
