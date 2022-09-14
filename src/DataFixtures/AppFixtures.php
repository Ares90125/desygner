<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->passwordHasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 2; $i++)
        {
            $admin = new User;
            $admin->setEmail("admin1$i@d.com");
            $admin->setName("Admin $i");
            $admin->setPassword($this->passwordHasher->hashPassword($admin, '123123123'));
            $admin->setRoles(['ROLE_ADMIN']);
            $manager->persist($admin);
        }

        for ($i = 1; $i <= 2; $i++)
        {
            $developer = new User;
            $developer->setEmail("developer$i@d.com");
            $developer->setName("FE Developer$i");
            $developer->setPassword($this->passwordHasher->hashPassword($developer, '123123123'));
            $developer->setRoles(['ROLE_ADMIN']);
            $manager->persist($developer);
        }

        for($i = 1; $i <= 10; $i++)
        {
            $user = new User;
            $user->setEmail("user${i}@d.com");
            $user->setName("User $i");
            $user->setPassword($this->passwordHasher->hashPassword($user, '123123123'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
