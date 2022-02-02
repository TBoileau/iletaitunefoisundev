<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\Continent;

use App\Adventure\Doctrine\Repository\ContinentRepository;
use App\Adventure\Entity\Continent;
use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class GetContinentTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsContinent(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = $client->getContainer()->get(ContinentRepository::class);

        $continent = $continentRepository->findOneBy([]);

        /** @var array{id: string, name: string} $content */
        $content = self::get($client, sprintf('/api/adventure/continents/%s', $continent->getId()));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertTrue(Ulid::isValid($content['id']));
        self::assertEquals($continent->getName(), $content['name']);
    }
}
