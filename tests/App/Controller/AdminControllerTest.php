<?php

namespace App\Tests\App\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

class AdminControllerTest extends BaseApiControllerTest
{
    private $image;


    // public function testSomething(): void
    // {

    //     $response = static::createClient()->request('GET', '/');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertJsonContains(['@id' => '/']);
    // }
    public function testImageCreate(): void
    {
        $this->createImage();

        $token = $this->getToken('ROLE_ADMIN');
        $client = $this->createClientWithCredentials($token);

        $client->request('POST', '/api/admin/images', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => [
                    'image'     =>  new UploadedFile($this->image, 'test.jpg')
                ],
                'parameters' => [
                    'provider'  =>  $this->faker->bothify('???????'),
                    'tags'      =>  [$this->faker->bothify('???'), $this->faker->bothify('????')]
                ]
            ]
        ]);
        $this->assertResponseIsSuccessful();


        $client->request('POST', '/api/admin/images', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'parameters' => [
                    'provider'  =>  $this->faker->bothify('???????'),
                    'tags'      =>  [$this->faker->bothify('???'), $this->faker->bothify('????')],
                    'url'       =>  $this->faker->imageUrl(640, 480, 'animals', true)
                ]
            ]
        ]);
        $this->assertResponseIsSuccessful();
    }

    private function createImage()
    {
        \copy('./test.jpg', 'temp.jpg');
        $this->image = './temp.jpg';
    }
}
