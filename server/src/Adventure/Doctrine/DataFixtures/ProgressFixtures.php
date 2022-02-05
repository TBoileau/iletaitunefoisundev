<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\World;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ProgressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Player> $players */
        $players = $manager->getRepository(Player::class)->findAll();

        /** @var World $world */
        $world = $manager->getRepository(World::class)->findOneBy([]);

        foreach ($players as $index => $player) {
            /** @var Continent $continent */
            $continent = $world->getContinents()->get($index);

            $finishedAt = new DateTimeImmutable('2022-01-01 00:00:00');

            /** @var Region $region */
            foreach ($continent->getRegions()->slice(0, $index + 1) as $region) {
                /** @var array<array-key, Quest> $quests */
                $quests = $region->getQuests()->slice(0, $index + 1);
                foreach ($quests as $k => $quest) {
                    $checkpoint = new Checkpoint();
                    $checkpoint->setJourney($player->getJourney());
                    $checkpoint->setQuest($quest);
                    $checkpoint->setStartedAt($finishedAt->sub(new DateInterval('PT59M')));
                    if ($k < count($quests) - 1) {
                        $checkpoint->setFinishedAt($finishedAt);
                    }
                    $manager->persist($checkpoint);

                    $finishedAt = $finishedAt->add(new DateInterval('PT1H'));
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
