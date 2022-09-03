<?php

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
    ){
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; ++$i) {
            $player = new Player();
            $player->setEmail(sprintf('player+%d@email.com', $i));
            $player->setNickName(sprintf('player+%d', $i));
            $player->setPassword($this->passwordHasher->hash('password'));
            $player->setGender($i % 2 ? Gender::Female : Gender::Male);
            $player->setId($this->ulidGenerator->generate());
            $manager->persist($player);
        }

        $manager->flush();
    }
}
