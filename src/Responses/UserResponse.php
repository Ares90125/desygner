<?php

namespace App\Responses;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class UserResponse
{
    public static function toArray(User|array|Collection $user)
    {
        if (\is_array($user) || $user instanceof Collection) {
            return \array_map(function ($item) { return self::toArray($item); }, \is_array($user) ? $user : $user->toArray());
        }
        return [
            'id'    =>  $user->getId(),
            'email' =>  $user->getEmail(),
            'roles' =>  $user->getRoles(),
            'name'  =>  $user->getName()
        ];
    }
}
