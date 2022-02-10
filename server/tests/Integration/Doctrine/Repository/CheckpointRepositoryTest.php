<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Doctrine\Repository\CheckpointRepository;
use App\Adventure\Doctrine\Repository\JourneyRepository;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Quest;
use App\Content\Doctrine\Repository\SessionRepository;
use App\Content\Entity\Quiz\Session;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CheckpointRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function shouldAttachSession(): void
    {
        self::bootKernel();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = self::getContainer()->get(QuestRepository::class);

        /** @var Quest $quest */
        $quest = $questRepository->find(125);

        /** @var JourneyRepository<Journey> $journeyRepository */
        $journeyRepository = self::getContainer()->get(JourneyRepository::class);

        /** @var Journey $journey */
        $journey = $journeyRepository->findOneBy([]);

        /** @var SessionRepository<Session> $sessionRepository */
        $sessionRepository = self::getContainer()->get(SessionRepository::class);

        /** @var Session $session */
        $session = $sessionRepository->findOneBy(['quiz' => $quest->getQuiz(), 'player' => $journey->getPlayer()]);

        /** @var CheckpointRepository<Checkpoint> $checkpointRepository */
        $checkpointRepository = self::getContainer()->get(CheckpointRepository::class);

        $checkpointRepository->createQueryBuilder('c')
            ->delete()
            ->where('c.quest = :quest')
            ->andWhere('c.journey = :journey')
            ->setParameter('quest', $quest)
            ->setParameter('journey', $journey)
            ->getQuery()
            ->execute();

        $checkpointRepository->attachSession($session);

        /** @var Checkpoint $checkpoint */
        $checkpoint = $checkpointRepository->createQueryBuilder('c')
            ->where('c.quest = :quest')
            ->andWhere('c.journey = :journey')
            ->setParameter('quest', $quest)
            ->setParameter('journey', $journey)
            ->getQuery()
            ->getSingleResult();

        self::assertEquals($session, $checkpoint->getSession());
    }

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

        /** @var array<array-key, int> $questsId */
        $questsId = $journey->getCheckpoints()
            ->map(static fn (Checkpoint $checkpoint): int => $checkpoint->getQuest()->getId()) /* @phpstan-ignore-line */
            ->toArray();

        /** @var Quest $quest */
        $quest = $queryBuilder
            ->where($queryBuilder->expr()->notIn('q.id', $questsId))
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult();

        $checkpoint = new Checkpoint();
        $checkpoint->setJourney($journey);
        $checkpoint->setStartedAt(new DateTimeImmutable());
        $checkpoint->setFinishedAt(new DateTimeImmutable());
        $checkpoint->setQuest($quest);

        $checkpointRepository->save($checkpoint);

        $checkpoint = $checkpointRepository->find($checkpoint->getId());

        self::assertNotNull($checkpoint);
        self::assertEquals($quest, $checkpoint->getQuest());
        self::assertEquals($journey, $checkpoint->getJourney());
    }
}
