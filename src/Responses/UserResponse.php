<?php

namespace App\Responses;

use App\Entity\User;

class UserResponse
{
    public static function toArray(User $user)
    {
        return [
            'id'    =>  $user->getId(),
            'email' =>  $user->getEmail(),
            'roles' =>  $user->getRoles(),
            'name'  =>  $user->getName()
        ];
    }
}
