<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\LibraryImage;
use App\Entity\User;
use App\Repository\LibraryImageRepository;

class LibraryService
{
    public function __construct(
        protected LibraryImageRepository $libraryImageRepository
    ) {}

    public function addToLibrary(User $user, Image $image): LibraryImage
    {
        $libraryImage = $this->libraryImageRepository->findOneBy(['user' => $user, 'image' => $image]);
        if ($libraryImage) return $libraryImage;
        $libraryImage = new LibraryImage;
        $libraryImage->setUser($user);
        $libraryImage->setImage($image);
        $this->libraryImageRepository->add($libraryImage, true);
        return $libraryImage;
    }
}
