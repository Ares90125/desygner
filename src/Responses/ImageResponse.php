<?php

namespace App\Responses;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class ImageResponse
{
    public static function toArray(Image|array|Collection $image)
    {
        if (\is_array($image) || $image instanceof Collection) {
            return \array_map(function ($item) { return self::toArray($item); }, \is_array($image) ? $image : $image->toArray());
        }
        return [
            'id'    =>  $image->getId(),
            'provider' =>  $image->getProvider(),
            'url'   =>  $image->getUrl(),
            // 'user'  =>  UserResponse::toArray($image->getUser())
        ];
    }
}
