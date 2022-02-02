<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use App\Adventure\Doctrine\Repository\WorldRepository;
use App\Adventure\Entity\World;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetContinentsByWorldTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsContinents(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        /** @var World $world */
        $world = $worldRepository->findOneBy(['name' => 'Monde']);

        /** @var array<array-key, array{id: string, name: string}> $content */
        $content = self::get($client, sprintf('/api/adventure/worlds/%s/continents', $world->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertCount(5, $content);
    }
}
