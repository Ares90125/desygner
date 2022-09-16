<?php

namespace App\Tests\App\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

abstract class BaseApiControllerTest extends ApiTestCase
{
    private $token;
    private $currentRole;

    protected $faker;

    protected UserRepository $userRepository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->faker = Factory::create();
    }

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();
        return static::createClient([], ['headers' => ['Authorization' => 'Bearer ' . $token]]);
    }
    protected function getToken($role = 'ROLE_USER'): string
    {
        if ($this->token && $this->currentRole === $role) return $this->token;
        $testUserMail = 'user@dsygner.com';
        switch ($role)
        {
            case 'ROLE_ADMIN':
                $testUserMail = 'admin@dsygner.com'; break;
            case 'ROLE_DEV':
                $testUserMail = 'dev@dsygner.com'; break;
            case 'ROLE_USER':
                $testUserMail = 'user@dsygner.com'; break;
        }

        $user = $this->userRepository->findOneByEmail($testUserMail);
        if (!$user) {

            $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

            $user = new User;
            $user->setEmail($testUserMail);
            $user->setName("User");
            $user->setPassword($passwordHasher->hashPassword($user, '123123123'));
            $user->setRoles([$role]);
            $this->userRepository->add($user, true);
        }
        $response = self::createClient()->request('POST', 'http://localhost/api/login_check', [
            'json'  => [
                    'email' => $testUserMail,
                    'password' => '123123123'
                ]
            ]);
        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());

        $this->token = $data->token;
        return $this->token;
    }

    protected function getUser($role)
    {
        $testUserMail = 'user@dsygner.com';
        switch ($role)
        {
            case 'ROLE_ADMIN':
                $testUserMail = 'admin@dsygner.com'; break;
            case 'ROLE_DEV':
                $testUserMail = 'dev@dsygner.com'; break;
            case 'ROLE_USER':
                $testUserMail = 'user@dsygner.com'; break;
        }

        $user = $this->userRepository->findOneByEmail($testUserMail);
        if (!$user) {

            $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

            $user = new User;
            $user->setEmail($testUserMail);
            $user->setName("User");
            $user->setPassword($passwordHasher->hashPassword($user, '123123123'));
            $user->setRoles([$role]);
            $this->userRepository->add($user, true);
        }
        return $this->userRepository->findOneByEmail($testUserMail);
    }
}
