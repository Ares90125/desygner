<?php

namespace App\Tests\App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;

class UserControllerTest extends ImageControllerTest
{
    public function testAddLibrary(): void
    {
        $image = $this->createImage();

        $token = $this->getToken('ROLE_USER');
        $client = $this->createClientWithCredentials($token);

        $client->request('PUT', '/api/user/images/' . $image->getId() . '/library');
        $this->assertResponseIsSuccessful();

        $lastImage = $this->getLastImage();
        $notFoundId = $lastImage->getId() + 1;
        $client->request('PUT', '/api/user/images/' . $notFoundId . '/library');
        $this->assertResponseStatusCodeSame(404);


        $token = $this->getToken('ROLE_DEV');
        $client = $this->createClientWithCredentials($token);

        $client->request('PUT', '/api/user/images/' . $image->getId() . '/library');
        $this->assertResponseStatusCodeSame(403);
    }
}
