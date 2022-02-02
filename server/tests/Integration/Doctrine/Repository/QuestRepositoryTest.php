<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\Entity\Difficulty;
use App\Adventure\Entity\Quest;
use App\Adventure\Entity\Region;
use App\Content\Doctrine\Repository\CourseRepository;
use App\Content\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Ulid;

final class QuestRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function getQuestsByRegionShouldReturnFiveQuests(): void
    {
        self::bootKernel();

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = self::getContainer()->get(RegionRepository::class);

        /** @var Region $region */
        $region = $regionRepository->findOneBy(['name' => 'Region 1']);

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = self::getContainer()->get(QuestRepository::class);

        $quests = $questRepository->getQuestsByRegion((string) $region->getId());

        self::assertCount(5, $quests);
    }

    /**
     * @test
     */
    public function getRelativesByQuestShouldReturnFiveQuests(): void
    {
        self::bootKernel();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = self::getContainer()->get(QuestRepository::class);

        /** @var Quest $quest */
        $quest = $questRepository->findOneBy([]);

        $relatives = $questRepository->getRelativesByQuest((string) $quest->getId());

        self::assertCount(1, $relatives);
    }

    /**
     * @test
     */
    public function getQuestByIdShouldReturnQuest(): void
    {
        self::bootKernel();

        /** @var CourseRepository<Course> $courseRepository */
        $courseRepository = self::getContainer()->get(CourseRepository::class);

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = self::getContainer()->get(QuestRepository::class);

        /** @var RegionRepository<Region> $regionRepository */
        $regionRepository = self::getContainer()->get(RegionRepository::class);

        /** @var Region $region */
        $region = $regionRepository->findOneBy([]);

        $id = new Ulid();

        /** @var Course $course */
        $course = $courseRepository->findOneBy([]);

        $quest = new Quest();
        $quest->setId($id);
        $quest->setName('Quest 0');
        $quest->setRegion($region);
        $quest->setDifficulty(Difficulty::Easy);
        $quest->setCourse($course);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($quest);

        $quest = $questRepository->getQuestById((string) $id);

        self::assertEquals('Quest 0', $quest->getName());
        self::assertEquals($region, $quest->getRegion());
    }

    /**
     * @test
     */
    public function getQuestByIdShouldRaiseAnException(): void
    {
        self::bootKernel();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = self::getContainer()->get(QuestRepository::class);

        $id = new Ulid();

        self::expectException(InvalidArgumentException::class);
        $questRepository->getQuestById((string) $id);
    }
}
