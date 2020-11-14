<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new User())
            ->setEmail("email@email.com")
            ->setPlainPassword("password")
            ->setRoles(["ROLE_ADMIN"]));
        $manager->flush();
    }
}
