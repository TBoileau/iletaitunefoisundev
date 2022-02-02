<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\Quest;

use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Entity\Quest;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetRelativesByQuestTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsOneRelativeQuest(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = self::getContainer()->get(QuestRepository::class);

        /** @var Quest $quest */
        $quest = $questRepository->findOneBy([]);

        /** @var array<array-key, array{id: string, name: string, difficulty: string}> $content */
        $content = self::get($client, sprintf('/api/adventure/quests/%s/relatives', $quest->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertCount(1, $content);
    }
}
