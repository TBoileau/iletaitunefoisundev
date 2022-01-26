<?php

declare(strict_types=1);

namespace App\Adventure\DataFixtures;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\World;
use App\Core\Uid\UlidGeneratorInterface;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ProgressFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Player> $players */
        $players = $manager->getRepository(Player::class)->findAll();

        /** @var World $world */
        $world = $manager->getRepository(World::class)->findOneBy([]);

        foreach ($players as $index => $player) {
            /** @var Continent $continent */
            $continent = $world->getContinents()->get($index);

            $passedAt = new DateTimeImmutable('2022-01-01 00:00:00');

            /** @var Region $region */
            foreach ($continent->getRegions()->slice(0, $index + 1) as $region) {
                /** @var Quest $quest */
                foreach ($region->getQuests()->slice(0, $index + 1) as $quest) {
                    $checkpoint = new Checkpoint();
                    $checkpoint->setId($this->ulidGenerator->generate());
                    $checkpoint->setJourney($player->getJourney());
                    $checkpoint->setQuest($quest);
                    $checkpoint->setPassedAt($passedAt);
                    $manager->persist($checkpoint);

                    $passedAt = $passedAt->add(new DateInterval('PT1H'));
                }
            }
            $manager->flush();
        }
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [PlayerFixtures::class, QuestFixtures::class];
    }
}
