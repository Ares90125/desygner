<?php

namespace App\Responses;

use App\Entity\Tag;
use Doctrine\Common\Collections\Collection;

class TagResponse
{
    public static function toArray(Tag|array|Collection $tag)
    {
        if (\is_array($tag) || $tag instanceof Collection) {
            return \array_map(function ($item) { return self::toArray($item); }, \is_array($tag) ? $tag : $tag->toArray());
        }
        return [
            'id'    =>  $tag->getId(),
            'text'  =>  $tag->getText()
        ];
    }
}
