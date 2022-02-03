<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;

final class ContinentTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function getItemShouldReturnContinent(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var Continent $continent */
        $continent = $this->findOneBy(Continent::class, [[]]);
        $client->request(Request::METHOD_GET, sprintf('/api/adventure/continents/%s', $continent->getId()));
        self::assertResponseIsSuccessful();
        self::assertMatchesResourceItemJsonSchema(Continent::class);
    }

    /**
     * @test
     */
    public function getSubresourceOfWorldShouldReturnContinents(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var World $world */
        $world = $this->findOneBy(World::class, [[]]);
        $response = $client->request(
            Request::METHOD_GET,
            sprintf('/api/adventure/worlds/%s/continents', $world->getId())
        );
        self::assertResponseIsSuccessful();
        self::assertCount(5, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(Continent::class);
    }
}
