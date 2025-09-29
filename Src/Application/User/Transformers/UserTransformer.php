<?php

namespace Application\User\Transformers;

use League\Fractal\TransformerAbstract;
use Domain\User\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform($data)
    {
        if ($data instanceof User) {
            return $this->transformUser($data);
        }


        if (is_array($data)) {
            $result = [];


            if (isset($data['token'])) {
                $result['token'] = $data['token'];
            }


            if (isset($data['verification'])) {
                $result['verification_message'] = $data['verification'];
            }


            if (isset($data['user']) && $data['user'] instanceof User) {
                $result['user'] = $this->transformUser($data['user']);
            }

            return $result;
        }

        return [];
    }

    private function transformUser(User $user): array
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
