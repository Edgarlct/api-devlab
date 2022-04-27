<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($i.'a@a.fr');
            $user->setRoles([]);
            $user->setLastName('ta');
            $user->setFirstName('ta');
            $user->setAge(199);
            $password = $this->hasher->hashPassword($user, 'a');

            $user->setPassword($password);
            $manager->persist($user);

        }
        $manager->flush();
    }
}
