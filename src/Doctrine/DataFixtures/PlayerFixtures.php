<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class PlayerFixtures extends Fixture
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private UlidGeneratorInterface $ulidGenerator
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $manager->persist(
                (new Player())
                    ->setEmail(sprintf('player+%d@email.com', $i))
                    ->setNickname(sprintf('player+%d', $i))
                    ->setPassword($this->passwordHasher->hash('password'))
                    ->setGender(0 === $i % 2 ? Gender::Female : Gender::Male)
                    ->setId($this->ulidGenerator->generate())
            );
        }

        $manager->flush();
    }
}
