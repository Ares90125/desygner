<?php

namespace App\Responses;

use App\Entity\Image;
use App\Entity\User;

class ImageResponse
{
    public static function toArray(Image $image)
    {
        return [
            'id'    =>  $image->getId(),
            'provider' =>  $image->getProvider(),
            'url'   =>  $image->getUrl(),
            // 'user'  =>  UserResponse::toArray($image->getUser())
        ];
    }
}
