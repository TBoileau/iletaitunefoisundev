<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;

final class PathFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [TrainingFixtures::class, PlayerFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $trainings = $manager->getRepository(Training::class)->findBy([], [], 2);

        $players = $manager->getRepository(Player::class)->findAll();

        foreach ($trainings as $training) {
            foreach ($players as $player) {
                $manager->persist(
                    (new Path())
                        ->setTraining($training)
                        ->setPlayer($player)
                        ->setBeganAt(new DateTimeImmutable())
                        ->setId($this->ulidGenerator->generate())
                );
            }

            $manager->flush();
        }
    }
}
