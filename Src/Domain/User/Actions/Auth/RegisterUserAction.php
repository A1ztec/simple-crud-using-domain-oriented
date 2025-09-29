<?php


namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Domain\User\Resources\UserResource;
use Domain\User\DataObjects\Auth\RegisterUserData;
use Domain\User\DataObjects\Auth\ReSendVerificationEmailData;

class RegisterUserAction
{

    public function __construct(private SendVerificationEmailAction $sendVerificationEmailAction) {}
    public function execute(RegisterUserData $data): UserResource
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

        $resource = $this->sendVerificationEmailAction->execute(new ReSendVerificationEmailData(email: $data->email));

        return UserResource::success(data: [
            'user' => $user,
            'verification' => $resource->getMessage(),
        ], code: 201, message: 'User registered successfully. Please verify your email.');
    }
}
