<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use App\Adventure\Doctrine\Repository\ContinentRepository;
use App\Adventure\Entity\Continent;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetRegionsByContinentTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsRegions(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = self::getContainer()->get(ContinentRepository::class);

        /** @var Continent $continent */
        $continent = $continentRepository->findOneBy(['name' => 'Continent 1']);

        /** @var array<array-key, array{id: string, name: string}> $content */
        $content = self::get($client, sprintf('/api/adventure/continents/%s/regions', $continent->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertCount(5, $content);
    }
}
