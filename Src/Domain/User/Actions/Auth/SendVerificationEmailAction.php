<?php

namespace Domain\User\Actions\Auth;


use Exception;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Domin\User\Resources\UserResource;
use Domain\User\Mails\VerificationCodeEmail;
use Domain\User\Actions\Auth\GenerateVerificationCodeAction;

class SendVerificationEmailAction
{
    public function __construct(private GenerateVerificationCodeAction $generateVerificationCodeAction) {}

    public function execute($user): UserResource
    {

        $code = $this->generateVerificationCodeAction->execute($user);
        try {
            Mail::to($user->email)->queue(new VerificationCodeEmail($code, $user->name));
            Log::info('Verification Code Send.');
            return UserResource::success(code: 200, message: 'Verification code sent to email');
        } catch (Exception $e) {
            Log::error('failed to send the verification code email' . $e->getMessage());
            return UserResource::error(message: 'failed to send the verification code', code: 400);
        }
    }
}
