<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\World;

use App\Adventure\Doctrine\Repository\WorldRepository;
use App\Adventure\Entity\World;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class GetWorldTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsWorld(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = $client->getContainer()->get(WorldRepository::class);

        $world = $worldRepository->findOneBy([]);

        /** @var array{id: string, name: string} $content */
        $content = self::get($client, sprintf('/api/adventure/worlds/%s', $world->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertTrue(Ulid::isValid($content['id']));
        self::assertEquals('Monde', $content['name']);
    }
}
