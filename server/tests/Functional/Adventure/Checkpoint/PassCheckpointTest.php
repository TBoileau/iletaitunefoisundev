<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\Checkpoint;

use App\Adventure\Doctrine\Repository\PlayerRepository;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PassCheckpointTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldSaveCheckpoint(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var PlayerRepository<Player> $playerRepository */
        $playerRepository = self::getContainer()->get(PlayerRepository::class);

        /** @var Player $player */
        $player = $playerRepository->findOneBy(['name' => 'Player 1']);

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = $client->getContainer()->get(QuestRepository::class);

        $queryBuilder = $questRepository->createQueryBuilder('q');

        /** @var Quest $quest */
        $quest = $queryBuilder
            ->where(
                $queryBuilder->expr()->notIn(
                    'q.id',
                    $player->getJourney()
                        ->getCheckpoints()
                        ->map(static fn (Checkpoint $checkpoint): string => (string) $checkpoint->getQuest()->getId())
                        ->toArray()
                )
            )
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult();

        self::post($client, '/api/adventure/checkpoints', [
            'quest' => (string) $quest->getId(),
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
