<?php

namespace Application\User\Controllers\Api;

use App\Http\Controllers\Controller;
use Domain\User\DataObjects\Auth\LoginUserData;
use Domain\User\DataObjects\Auth\VerifyEmailData;
use Domain\User\Actions\Auth\LoginUserAction;
use Domain\User\DataObjects\Auth\RegisterUserData;
use Application\User\ViewModels\UserViewModel;
use Application\User\Requests\Auth\LoginRequest;
use Domain\User\Actions\Auth\RegisterUserAction;
use Domain\User\Actions\Auth\VerifyUserEmailAction;
use Application\User\Requests\Auth\VerifyEmailRequest;
use Application\User\Requests\Auth\RegisterUserRequest;
use Domain\User\DataObjects\Auth\ReSendVerificationEmailData;
use Domain\User\Actions\Auth\SendVerificationEmailAction;

class AuthController extends Controller
{


    public function register(RegisterUserRequest $request, RegisterUserAction $registerUserAction)
    {
        $data = $request->validated();
        $dto = new RegisterUserData(...$data);
        $resource = $registerUserAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }

    public function login(LoginRequest $request, LoginUserAction $loginUserAction)
    {
        $data = $request->validated();
        $dto = new LoginUserData(...$data);
        $resource = $loginUserAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }

    public function verifyEmail(VerifyEmailRequest $request, VerifyUserEmailAction $verifyUserEmailAction)
    {
        $data = $request->validated();
        $dto = new VerifyEmailData(...$data);
        $resource = $verifyUserEmailAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }

    public function reSendVerificationCode(VerifyEmailRequest $request, SendVerificationEmailAction $sendVerificationEmailAction)
    {
        $data = $request->validated();
        $dto = new ReSendVerificationEmailData(...$data);
        $resource = $sendVerificationEmailAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }
}
