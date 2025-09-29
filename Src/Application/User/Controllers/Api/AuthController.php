<?php

namespace Application\User\Controllers\Api;

use App\Http\Controllers\Controller;
use Application\User\ViewModels\UserViewModel;
use Domain\User\DataObjects\UserData;
use Domain\User\Actions\Auth\LoginUserAction;
use Application\User\Requests\Auth\LoginRequest;
use Domain\User\Actions\Auth\RegisterUserAction;
use Domain\User\Actions\Auth\VerifyUserEmailAction;
use Application\User\Requests\Auth\VerifyEmailRequest;
use Application\User\Requests\Auth\RegisterUserRequest;
use Domain\User\Actions\Auth\SendVerificationEmailAction;

class AuthController extends Controller
{
    public function __construct(
        private RegisterUserAction $registerUserAction,
        private LoginUserAction $loginUserAction,
        private VerifyUserEmailAction $verifyUserEmailAction,
        private SendVerificationEmailAction $sendVerificationEmailAction
    ) {}

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $dto = new UserData(...$data);
        $resource = $this->registerUserAction->execute($dto);
        return new UserViewModel($resource);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $dto = new UserData(...$data);
        $resource = $this->loginUserAction->execute($dto);
        return new UserViewModel($resource);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $data = $request->validated();
        $dto = new UserData(...$data);
        $resource = $this->verifyUserEmailAction->execute($dto);
        return new UserViewModel($resource);
    }

    public function reSendVerificationCode(VerifyEmailRequest $request)
    {
        $data = $request->validated();
        $dto = new UserData(...$data);
        $resource = $this->sendVerificationEmailAction->execute($dto);
        return new UserViewModel($resource);
    }
}
