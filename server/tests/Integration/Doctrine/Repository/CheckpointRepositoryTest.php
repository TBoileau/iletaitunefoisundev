<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\CheckpointRepository;
use App\Adventure\Doctrine\Repository\JourneyRepository;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Quest;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Ulid;

final class CheckpointRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function shouldSaveCheckpoint(): void
    {
        self::bootKernel();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = self::getContainer()->get(QuestRepository::class);

        /** @var JourneyRepository<Journey> $journeyRepository */
        $journeyRepository = self::getContainer()->get(JourneyRepository::class);

        /** @var CheckpointRepository<Checkpoint> $checkpointRepository */
        $checkpointRepository = self::getContainer()->get(CheckpointRepository::class);

        /** @var Journey $journey */
        $journey = $journeyRepository->findOneBy([]);

        $queryBuilder = $questRepository->createQueryBuilder('q');

        /** @var Quest $quest */
        $quest = $queryBuilder
            ->where(
                $queryBuilder->expr()->notIn(
                    'q.id',
                    $journey->getCheckpoints()
                        ->map(static fn (Checkpoint $checkpoint): string => (string) $checkpoint->getQuest()->getId())
                        ->toArray()
                )
            )
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult();

        $checkpoint = new Checkpoint();
        $checkpoint->setId(new Ulid());
        $checkpoint->setJourney($journey);
        $checkpoint->setPassedAt(new DateTimeImmutable());
        $checkpoint->setQuest($quest);

        $checkpointRepository->save($checkpoint);

        $checkpoint = $checkpointRepository->find($checkpoint->getId());

        self::assertNotNull($checkpoint);
        self::assertTrue(Ulid::isValid((string) $checkpoint->getId()));
        self::assertEquals($quest, $checkpoint->getQuest());
        self::assertEquals($journey, $checkpoint->getJourney());
    }
}
