<?php

namespace Application\User\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Post;
use Domain\User\Actions\Auth\LoginUserAction;
use Spatie\RouteAttributes\Attributes\Prefix;
use Application\User\ViewModels\UserViewModel;
use Domain\User\DataObjects\Auth\LoginUserData;
use Application\User\Requests\Auth\LoginRequest;
use Domain\User\Actions\Auth\RegisterUserAction;
use Domain\User\DataObjects\Auth\VerifyEmailData;
use Domain\User\DataObjects\Auth\RegisterUserData;
use Domain\User\Actions\Auth\VerifyUserEmailAction;
use Application\User\Requests\Auth\VerifyEmailRequest;
use Application\User\Requests\Auth\RegisterUserRequest;
use Domain\User\Actions\Auth\SendVerificationEmailAction;
use Domain\User\DataObjects\Auth\ReSendVerificationEmailData;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('auth')]
class AuthController extends Controller
{


    #[Post(
        uri: '/register',
        name: 'auth.register'
    )]
    public function register(RegisterUserRequest $request, RegisterUserAction $registerUserAction)
    {
        $data = $request->validated();
        $dto = new RegisterUserData(...$data);
        $resource = $registerUserAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }

    #[Post(
        uri: '/login',
        name: 'auth.login',
        middleware: ['throttle:5,1']
    )]
    public function login(LoginRequest $request, LoginUserAction $loginUserAction)
    {
        $data = $request->validated();
        $dto = new LoginUserData(...$data);
        $resource = $loginUserAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }

    #[Post(
        uri: '/verify-email',
        name: 'auth.verify-email'
    )]
    public function verifyEmail(VerifyEmailRequest $request, VerifyUserEmailAction $verifyUserEmailAction)
    {
        $data = $request->validated();
        $dto = new VerifyEmailData(...$data);
        $resource = $verifyUserEmailAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }

    #[Post(
        uri: '/resend-verification-code',
        name: 'auth.resend-verification-code'
    )]
    public function reSendVerificationCode(VerifyEmailRequest $request, SendVerificationEmailAction $sendVerificationEmailAction)
    {
        $data = $request->validated();
        $dto = new ReSendVerificationEmailData(...$data);
        $resource = $sendVerificationEmailAction->execute($dto);
        return (new UserViewModel($resource))->toResponse();
    }
}
