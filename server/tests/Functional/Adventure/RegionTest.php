<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Region;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;

final class RegionTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function getItemShouldReturnRegion(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var Region $region */
        $region = $this->findOneBy(Region::class, [[]]);
        $client->request(Request::METHOD_GET, sprintf('/api/adventure/regions/%s', $region->getId()));
        self::assertResponseIsSuccessful();
        self::assertMatchesResourceItemJsonSchema(Region::class);
    }

    /**
     * @test
     */
    public function getSubresourceOfContinentShouldReturnRegions(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var Continent $continent */
        $continent = $this->findOneBy(Continent::class, [[]]);
        $response = $client->request(
            Request::METHOD_GET,
            sprintf('/api/adventure/continents/%s/regions', $continent->getId())
        );
        self::assertResponseIsSuccessful();
        self::assertCount(5, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(Region::class);
    }
}
