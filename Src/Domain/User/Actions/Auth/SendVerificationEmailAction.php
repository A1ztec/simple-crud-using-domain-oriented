<?php

namespace Domain\User\Actions\Auth;


use Exception;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Domain\User\Resources\UserResource;
use Domain\User\Mails\VerificationCodeEmail;
use Domain\User\Actions\Auth\GenerateVerificationCodeAction;
use Domain\User\DataObjects\Auth\ReSendVerificationEmailData;

class SendVerificationEmailAction
{
    public function __construct(private GenerateVerificationCodeAction $generateVerificationCodeAction) {}

    public function execute(ReSendVerificationEmailData $data): UserResource
    {

        $resource = $this->generateVerificationCodeAction->execute($data);
        try {
            Mail::to($data->email)->queue(new VerificationCodeEmail($resource->getData()));
            Log::info('Verification Code Send.');
            return UserResource::success(code: 200, message: 'Verification code sent to email');
        } catch (Exception $e) {
            Log::error('failed to send the verification code email' . $e->getMessage());
            return UserResource::error(message: 'failed to send the verification code', code: 400);
        }
    }
}
