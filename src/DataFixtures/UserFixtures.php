<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new User())
            ->setEmail("user+suspended@email.com")
            ->setPlainPassword("password")
            ->setNickname("user+suspended")
            ->setSuspendedAt(new DateTimeImmutable()));
        $manager->persist((new User())
            ->setEmail("user+1@email.com")
            ->setPlainPassword("password")
            ->setNickname("user+1"));
        $manager->persist((new User())
            ->setEmail("admin@email.com")
            ->setPlainPassword("password")
            ->setNickname("admin")
            ->setRoles(["ROLE_ADMIN"]));
        $manager->flush();
    }
}
