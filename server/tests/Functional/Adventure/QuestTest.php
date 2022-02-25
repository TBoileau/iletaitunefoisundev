<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Doctrine\Repository\QuestRepository;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Security\Entity\User;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class QuestTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function shouldReturnCheckpoint(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var User $user */
        $user = $this->findOneBy(User::class, [['email' => 'user+1@email.com']]);
        /** @var Player $player */
        $player = $user->getPlayer();
        /** @var Checkpoint $checkpoint */
        $checkpoint = $player->getJourney()->getCheckpoints()->first();
        $quest = $checkpoint->getQuest();

        $client->request(Request::METHOD_GET, sprintf('/api/adventure/quests/%s/checkpoint', $quest->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function shouldStartQuest(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var User $user */
        $user = $this->findOneBy(User::class, [['email' => 'user+1@email.com']]);
        /** @var Player $player */
        $player = $user->getPlayer();
        /** @var Checkpoint $checkpoint */
        $checkpoint = $player->getJourney()->getCheckpoints()->first();
        $playerQuest = $checkpoint->getQuest();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = $this->getRepository(Quest::class);

        /** @var Quest $quest */
        $quest = $questRepository->createQueryBuilder('q')
            ->where('q != :quest')
            ->setParameter('quest', $playerQuest)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $client->request(
            Request::METHOD_POST,
            sprintf('/api/adventure/quests/%s/start', $quest->getId()),
            ['json' => []]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function startQuestShouldRaiseAnException(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var User $user */
        $user = $this->findOneBy(User::class, [['email' => 'user+1@email.com']]);
        /** @var Player $player */
        $player = $user->getPlayer();
        /** @var Checkpoint $checkpoint */
        $checkpoint = $player->getJourney()->getCheckpoints()->first();
        $client->request(
            Request::METHOD_POST,
            sprintf('/api/adventure/quests/%s/start', $checkpoint->getQuest()->getId()),
            ['json' => []]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function shouldFinishQuest(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var User $user */
        $user = $this->findOneBy(User::class, [['email' => 'user+1@email.com']]);
        /** @var Player $player */
        $player = $user->getPlayer();
        /** @var Checkpoint $checkpoint */
        $checkpoint = $player->getJourney()
            ->getCheckpoints()
            ->filter(static fn (Checkpoint $checkpoint): bool => null === $checkpoint->getFinishedAt())
            ->first();
        $quest = $checkpoint->getQuest();

        $client->request(
            Request::METHOD_POST,
            sprintf('/api/adventure/quests/%s/finish', $quest->getId()),
            ['json' => []]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
    }

    /**
     * @test
     */
    public function finishQuestThatHasBeenAlreadyFinishedShouldRaiseAnException(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var User $user */
        $user = $this->findOneBy(User::class, [['email' => 'user+2@email.com']]);
        /** @var Player $player */
        $player = $user->getPlayer();
        /** @var Checkpoint $checkpoint */
        $checkpoint = $player->getJourney()
            ->getCheckpoints()
            ->filter(static fn (Checkpoint $checkpoint): bool => null !== $checkpoint->getFinishedAt())
            ->first();
        $client->request(
            Request::METHOD_POST,
            sprintf('/api/adventure/quests/%s/finish', $checkpoint->getQuest()->getId()),
            ['json' => []]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function finishQuestThatIsNotStartedShouldRaiseAnException(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var User $user */
        $user = $this->findOneBy(User::class, [['email' => 'user+1@email.com']]);
        /** @var Player $player */
        $player = $user->getPlayer();

        /** @var Checkpoint $checkpoint */
        $checkpoint = $player->getJourney()->getCheckpoints()->first();
        $playerQuest = $checkpoint->getQuest();

        /** @var QuestRepository<Quest> $questRepository */
        $questRepository = $this->getRepository(Quest::class);

        /** @var Quest $quest */
        $quest = $questRepository->createQueryBuilder('q')
            ->where('q != :quest')
            ->setParameter('quest', $playerQuest)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $client->request(
            Request::METHOD_POST,
            sprintf('/api/adventure/quests/%s/finish', $quest->getId()),
            ['json' => []]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function getItemShouldReturnQuest(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var Quest $quest */
        $quest = $this->findOneBy(Quest::class, [[]]);
        $client->request(Request::METHOD_GET, sprintf('/api/adventure/quests/%s', $quest->getId()));
        self::assertResponseIsSuccessful();
        self::assertMatchesResourceItemJsonSchema(Quest::class);
    }
}
