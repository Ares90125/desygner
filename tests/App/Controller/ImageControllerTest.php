<?php

namespace App\Tests\App\Controller;


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
    }
}
