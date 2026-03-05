<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setName('User ' . $i);
            $user->setPlainPassword('password' . $i);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
