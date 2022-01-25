<?php

declare(strict_types=1);

namespace App\Adventure\DataFixtures;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Level;
use App\Adventure\Entity\Map;
use App\Core\Uid\UlidGeneratorInterface;
use App\Security\DataFixtures\UserFixtures;
use App\Security\Entity\User;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class JourneyFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, User> $users */
        $users = $manager->getRepository(User::class)->findAll();

        /** @var Map $map */
        $map = $manager->getRepository(Map::class)->findOneBy(['previous' => null]);

        /** @var Level $level */
        $level = $manager->getRepository(Level::class)->findOneBy(['map' => $map, 'previous' => null]);

        foreach ($users as $index => $user) {
            $journey = $this->createJourney($user, $level);

            $manager->persist($journey);

            $passedAt = new DateTimeImmutable('2022-01-01 00:00:00');

            for ($i = 1; $i <= ($index * 10 + 5); ++$i) {
                $manager->persist($this->createCheckpoint($journey, $passedAt));
                $passedAt = $passedAt->add(new DateInterval('PT1H'));
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [MapFixtures::class, UserFixtures::class];
    }

    private function createJourney(User $user, Level $level): Journey
    {
        $journey = new Journey();
        $journey->setId($this->ulidGenerator->generate());
        $journey->setUser($user);
        $journey->setCurrentLevel($level);

        return $journey;
    }

    private function createCheckpoint(Journey $journey, DateTimeImmutable $passedAt): Checkpoint
    {
        $checkpoint = new Checkpoint();
        $checkpoint->setId($this->ulidGenerator->generate());
        $checkpoint->setJourney($journey);
        $checkpoint->setPassedAt($passedAt);

        /** @var Level $level */
        $level = $journey->getCurrentLevel();
        $checkpoint->setLevel($level);

        /** @var Level $nextLevel */
        $nextLevel = $level->getNext();
        $journey->setCurrentLevel($nextLevel);

        return $checkpoint;
    }
}
