<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use IncentiveFactory\Domain\Path\Level;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;

final class TrainingFixtures extends Fixture
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; ++$i) {
            $manager->persist(
                (new Training())
                    ->setName(sprintf('Training %d', $i))
                    ->setDescription(sprintf('Description %d', $i))
                    ->setSlug(sprintf('training+%d', $i))
                    ->setSkills(sprintf('Skill %d', $i))
                    ->setPrerequisites(sprintf('Prerequisite %d', $i))
                    ->setLevel(Level::cases()[$i % 3])
                    ->setId($this->ulidGenerator->generate())
                    ->setPublishedAt(new DateTimeImmutable())
                    ->setImage('image.png')
            );
        }

        $manager->flush();
    }
}
