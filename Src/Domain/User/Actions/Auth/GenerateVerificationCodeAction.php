<?php

namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Domain\User\Resources\UserResource;
use Domain\User\Resources\UserNotFoundValidationResource;
use Domain\User\Resources\Contracts\UserResourceInterface;
use Domain\User\DataObjects\Auth\ReSendVerificationEmailData;
use Domain\User\Resources\GenerateVerificationCodeFailedResource;
use Domain\User\Resources\GenerateVerificationCodeSuccessResource;


class GenerateVerificationCodeAction
{
    public function execute( ReSendVerificationEmailData $data): UserResourceInterface
    {
        $user = User::query()->whereEmail($data->email)->first();

        if (!$user) {
            return (new UserNotFoundValidationResource());
        }


        $verificationCode = rand(1000, 9999);

        DB::beginTransaction();

        try {
            $user->verification_code = $verificationCode;
            $user->verification_code_expires_at = now()->addMinutes(10);
            $user->save();

            DB::commit();

            return (new GenerateVerificationCodeSuccessResource($user));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Could not generate verification code: ' . $e->getMessage());
            return (new GenerateVerificationCodeFailedResource());
        }
    }
}
