<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Security\Entity\User;
use App\Domain\Shared\Uuid\UlidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    public function __construct(
        private UlidGeneratorInterface $ulidGenerator,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; ++$i) {
            $manager->persist($this->createUser($i));
        }
        $manager->flush();
    }

    private function createUser(int $index): User
    {
        $user = new User();
        $user->setId($this->ulidGenerator->generate());
        $user->setEmail(sprintf('user+%d@email.com', $index));
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
        $this->setReference(sprintf('user+%d', $index), $user);

        return $user;
    }
}
