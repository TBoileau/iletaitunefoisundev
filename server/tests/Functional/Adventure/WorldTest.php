<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Entity\World;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;

final class WorldTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function getItemShouldReturnWorld(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var World $world */
        $world = $this->findOneBy(World::class, [[]]);
        $client->request(Request::METHOD_GET, sprintf('/api/adventure/worlds/%s', $world->getId()));
        self::assertResponseIsSuccessful();
        self::assertMatchesResourceItemJsonSchema(World::class);
    }

    /**
     * @test
     */
    public function getCollectionShouldReturnWorlds(): void
    {
        $client = self::createAuthenticatedClient();
        $response = $client->request(Request::METHOD_GET, '/api/adventure/worlds');
        self::assertResponseIsSuccessful();
        self::assertCount(1, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(World::class);
    }
}
