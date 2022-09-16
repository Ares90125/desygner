<?php

namespace App\Tests\App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;

class ImageControllerTest extends BaseApiControllerTest
{
    public function testList(): void
    {
        // $response = static::createClient()->request('GET', '/');

        // $this->assertResponseIsSuccessful();
        // $this->assertJsonContains(['@id' => '/']);
        $token = $this->getToken('ROLE_ADMIN');
        $client = $this->createClientWithCredentials($token);

        $client->request('GET', '/api/images', [
            'extra' =>  [
                'parameters'    =>  [
                    'page'  =>  1,
                    'size'  =>  50,
                    'q'     =>  $this->faker->bothify('???')
                ]
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $token = $this->getToken('ROLE_DEV');
        $client = $this->createClientWithCredentials($token);

        $client->request('GET', '/api/images', [
            'extra' =>  [
                'parameters'    =>  [
                    'page'  =>  1,
                    'size'  =>  50,
                    'q'     =>  $this->faker->bothify('???')
                ]
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $token = $this->getToken('ROLE_USER');
        $client = $this->createClientWithCredentials($token);

        $client->request('GET', '/api/images', [
            'extra' =>  [
                'parameters'    =>  [
                    'page'  =>  1,
                    'size'  =>  50,
                    'q'     =>  $this->faker->bothify('???')
                ]
            ]
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testRetrieve(): void
    {
        $image = $this->createImage();
        print('image created, id: ' . $image->getId());

        $token = $this->getToken('ROLE_ADMIN');
        $client = $this->createClientWithCredentials($token);

        $client->request('GET', "/api/images/" . $image->getId());
        $this->assertResponseIsSuccessful();

        $lastImage = $this->getLastImage();
        $notFoundId = $lastImage->getId() + 1;

        $client->request('GET', "/api/images/" . $notFoundId);
        $this->assertResponseStatusCodeSame(404);
    }

    private function createImage(): Image
    {
        $imageRepository = static::getContainer()->get(ImageRepository::class);
        $user = $this->getUser('ROLE_ADMIN');
        $image = new Image;
        $image->setUser($user);
        // $image->setUrl($this->faker->url());
        $image->setUrl('https://test.com/test.png');
        $image->setProvider($this->faker->bothify('????'));
        $imageRepository->add($image, true);
        return $image;
    }
    private function getLastImage(): Image
    {
        /**
         * @var ImageRepository $imageRepository
         */
        $imageRepository = static::getContainer()->get(ImageRepository::class);
        return $imageRepository->findOneBy([], ['id' => 'desc']);
    }
}
