<?php

declare(strict_types=1);

namespace App\Adventure\DataFixtures;

use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Core\Uid\UlidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class QuestFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Region> $regions */
        $regions = $manager->getRepository(Region::class)->findAll();

        $relative = null;

        foreach ($regions as $region) {
            for ($i = 1; $i <= 5; ++$i) {
                $quest = new Quest();
                $quest->setId($this->ulidGenerator->generate());
                $quest->setName(sprintf('Quest %d', $i));
                $quest->setRegion($region);
                $quest->setDifficulty(match ($i) {
                    1, 2 => Difficulty::Easy,
                    3, 4 => Difficulty::Normal,
                    default => Difficulty::Hard,
                });
                if (null !== $relative) {
                    $quest->getRelatives()->add($relative);
                    $relative->getRelatives()->add($relative);
                }
                $manager->persist($quest);
                $relative = $quest;
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [RegionFixtures::class];
    }
}
