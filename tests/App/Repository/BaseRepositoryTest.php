<?php

namespace App\Tests\App\Repository;

use App\Entity\Image;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class BaseRepositoryTest extends KernelTestCase
{
    protected $faker;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
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

        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail($testUserMail);
        if (!$user) {

            $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

            $user = new User;
            $user->setEmail($testUserMail);
            $user->setName("User");
            $user->setPassword($passwordHasher->hashPassword($user, '123123123'));
            $user->setRoles([$role]);
            $userRepository->add($user, true);
        }
        return $userRepository->findOneByEmail($testUserMail);
    }
}
