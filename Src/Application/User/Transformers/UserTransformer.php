<?php

namespace Application\User\Transformers;

use League\Fractal\TransformerAbstract;
use Domain\User\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified' => !is_null($user->email_verified_at),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
