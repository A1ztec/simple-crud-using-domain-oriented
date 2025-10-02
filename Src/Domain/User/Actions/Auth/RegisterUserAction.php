<?php


namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Domain\User\Resources\UserResource;
use Domain\User\DataObjects\Auth\RegisterUserData;
use Domain\User\DataObjects\Auth\ReSendVerificationEmailData;
use Domain\User\Resources\Contracts\UserResourceInterface;
use Domain\User\Resources\UserRegisterFailedResource;
use Domain\User\Resources\UserRegisterSuccessResource;

class RegisterUserAction
{

    public function __construct(private SendVerificationEmailAction $sendVerificationEmailAction) {}
    public function execute(RegisterUserData $data): UserResourceInterface
    {
        if (User::query()->whereEmail($data->email)->exists()) {
            return (new  UserRegisterFailedResource(message: 'User already exists'));
        }

        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'password' => Hash::make($data->password),
        ]);

        $resource = $this->sendVerificationEmailAction->execute(new ReSendVerificationEmailData(email: $data->email));

        return (new UserRegisterSuccessResource(data: $user));
    }
}
