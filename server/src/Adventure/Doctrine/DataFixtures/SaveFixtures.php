<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Save;
use App\Core\Uid\UlidGeneratorInterface;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SaveFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Player> $players */
        $players = $manager->getRepository(Player::class)->findAll();

        foreach ($players as $index => $player) {
            $save = new Save();
            $save->setId($this->ulidGenerator->generate());
            $save->setSavedAt(new DateTimeImmutable());
            $save->setPlayer($player);
            $player->setSave($save);

            /** @var Quest $quest */
            $quest = $manager->getRepository(Quest::class)
                ->createQueryBuilder('q')
                ->addSelect('r')
                ->addSelect('c')
                ->addSelect('w')
                ->join('q.region', 'r')
                ->join('r.continent', 'c')
                ->join('c.world', 'w')
                ->where('q NOT IN (:quests)')
                ->setParameter(
                    'quests',
                    $player
                        ->getJourney()
                        ->getCheckpoints()
                        ->map(static fn (Checkpoint $checkpoint) => $checkpoint->getQuest())
                        ->toArray()
                )
                ->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();

            /** @var Checkpoint $checkpoint */
            $checkpoint = $player->getJourney()->getCheckpoints()->first();

            match ($index) {
                0 => $save->setWorld($quest->getRegion()->getContinent()->getWorld()),
                1 => $save->setContinent($quest->getRegion()->getContinent()),
                2 => $save->setRegion($quest->getRegion()),
                3 => $save->setQuest($quest),
                default => $save->setCheckpoint($checkpoint),
            };
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [ProgressFixtures::class];
    }
}
