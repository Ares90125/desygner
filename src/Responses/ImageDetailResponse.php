<?php

namespace App\Responses;

use App\Entity\Image;
use App\Entity\User;

class ImageDetailResponse
{
    public static function toArray(Image $image)
    {
        return [
            'id'    =>  $image->getId(),
            'provider' =>  $image->getProvider(),
            'url'   =>  $image->getUrl(),
            'tags'  =>  TagResponse::toArray($image->getTags()),
            'user_id'   =>  $image->getUser()->getId()
        ];
    }
}
