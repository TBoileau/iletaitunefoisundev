<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\Quest;

use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Entity\Quest;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class GetQuestTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsQuest(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = $client->getContainer()->get(QuestRepository::class);

        /** @var Quest $quest */
        $quest = $questRepository->findOneBy([]);

        /** @var array{id: string, name: string} $content */
        $content = self::get($client, sprintf('/api/adventure/quests/%s', $quest->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertTrue(Ulid::isValid($content['id']));
        self::assertEquals($quest->getName(), $content['name']);
    }
}
