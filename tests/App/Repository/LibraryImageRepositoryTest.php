<?php

namespace App\Tests\App\Repository;

use App\Entity\Image;
use App\Entity\User;
use App\Repository\ImageRepository;
use App\Repository\LibraryImageRepository;
use App\Service\LibraryService;

class LibraryImageRepositoryTest extends BaseRepositoryTest
{
    public function testGetLibraryImages(): void
    {
        $user = $this->createUserLibrary();

        /**
         * @var LibraryImageRepository $libraryImageRepository
         */
        $libraryImageRepository = static::getContainer()->get(LibraryImageRepository::class);
        $images = $libraryImageRepository->getLibraryImages($user);

        // $this->assertNotEmpty($images);
        $this->assertEquals(1, count($images));

    }

    private function createUserLibrary(): User
    {
        $imageRepository = static::getContainer()->get(ImageRepository::class);

        $admin = $this->getUser('ROLE_ADMIN');
        $image = new Image();
        $image->setUser($admin);
        $image->setUrl('http://test.com/test.png');

        $imageRepository->add($image, true);

        $user = $this->getUser('ROLE_USER');

        /**
         * @var LibraryService $libraryService
         */
        $libraryService = static::getContainer()->get(LibraryService::class);
        $libraryImage = $libraryService->addToLibrary($user, $image);

        $this->assertNotNull($libraryImage);
        return $user;
    }
}
