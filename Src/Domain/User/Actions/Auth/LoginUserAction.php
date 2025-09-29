<?php


namespace Domain\User\Actions\Auth;

use Domain\User\DataObjects\Auth\LoginUserData;
use Domain\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Domain\User\Resources\UserResource;

class LoginUserAction
{
    public function execute(LoginUserData $data): UserResource
    {
        $user = User::query()->whereEmail($data->email)->first();

        $validPassword = $user ? Hash::check($data->password, $user->password) : false;

        if (!$user || !$validPassword) {
            return UserResource::error(message: 'Invalid credentials', code: 401);
        }

        if (!$user->hasVerifiedEmail()) {
            return UserResource::error(message: 'Email not verified', code: 400);
        }

        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $e) {
            Log::error('Could not create token for user: ' . $e->getMessage());
            return UserResource::error(message: 'Could not create token', code: 500);
        }

        return UserResource::success(data: [
            'token' => $token,
            'user' => $user,
        ], code: 200, message: 'Login successful');
    }
}
