<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\DataFixtures;

use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Adventure\Entity\Type;
use App\Content\Doctrine\DataFixtures\CourseFixtures;
use App\Content\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class QuestFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Region> $regions */
        $regions = $manager->getRepository(Region::class)->findAll();

        /** @var array<array-key, Course> $courses */
        $courses = $manager->getRepository(Course::class)->findAll();

        $relative = null;

        $courseIndex = 0;

        foreach ($regions as $region) {
            for ($i = 1; $i <= 5; ++$i) {
                $quest = new Quest();
                $quest->setName(sprintf('Quest %d', $i));
                $quest->setRegion($region);
                $quest->setCourse($courses[$courseIndex]);
                $quest->setDifficulty(match ($i) {
                    1, 2 => Difficulty::Easy,
                    3, 4 => Difficulty::Normal,
                    default => Difficulty::Hard,
                });
                $quest->setType(match ($i % 2) {
                    0 => Type::Main,
                    default => Type::Side,
                });
                if (null !== $relative) {
                    $quest->getRelatives()->add($relative);
                    $relative->getRelatives()->add($relative);
                }
                $manager->persist($quest);
                $relative = $quest;

                if (1 === $i) {
                    $region->setStart($quest);
                }

                ++$courseIndex;
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [RegionFixtures::class, CourseFixtures::class];
    }
}
