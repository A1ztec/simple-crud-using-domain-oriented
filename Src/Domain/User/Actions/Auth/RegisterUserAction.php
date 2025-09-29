<?php


namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Domin\User\Resources\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute($data): UserResource
    {
        if (User::query()->whereEmail($data->email)->exists()) {
            return UserResource::error(message: 'User already exists', code: 400);
        }

        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'password' => Hash::make($data->password),
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $e) {
            Log::error('Could not create token for user: ' . $e->getMessage());
            return UserResource::error(message: 'Could not create token', code: 500);
        }

        return UserResource::success(data: [
            'user' => $user,
            'token' => $token,
        ], code: 201, message: 'User registered successfully');
    }
}
