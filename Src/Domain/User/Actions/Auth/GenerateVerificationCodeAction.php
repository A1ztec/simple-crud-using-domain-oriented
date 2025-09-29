<?php

namespace Domain\User\Actions\Auth;

use Domain\User\Models\User;
use Domin\User\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class GenerateVerificationCodeAction
{
    public function execute($data): UserResource
    {
        $user = User::query()->whereEmail($data->email)->first();

        if (!$user) {
            return UserResource::error(message: 'User not found', code: 404);
        }


        $verificationCode = rand(1000, 9999);

        DB::beginTransaction();

        try {
            $user->verification_code = $verificationCode;
            $user->verification_code_expires_at = now()->addMinutes(10);
            $user->save();

            DB::commit();

            return  UserResource::success(data: $user, message: 'Verification code generated successfully', code: 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Could not generate verification code: ' . $e->getMessage());
            return UserResource::error(message: 'Could not generate verification code', code: 500);
        }
    }
}
