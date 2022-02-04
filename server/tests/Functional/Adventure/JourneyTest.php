<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Tests\Functional\AuthenticatedClientTrait;
use App\Tests\Functional\DatabaseAccessTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class JourneyTest extends ApiTestCase
{
    use AuthenticatedClientTrait;
    use DatabaseAccessTrait;

    /**
     * @test
     */
    public function shouldReturnJourney(): void
    {
        $client = self::createAuthenticatedClient();
        $client->request(
            Request::METHOD_GET,
            '/api/adventure/journeys/1',
        );
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertMatchesResourceItemJsonSchema(Journey::class);
    }

    /**
     * @test
     */
    public function getItemShouldReturnCheckpoints(): void
    {
        $client = self::createAuthenticatedClient();
        $this->init($client);
        /** @var Journey $journey */
        $journey = $this->findOneBy(Journey::class, [[]]);
        $client->request(Request::METHOD_GET, sprintf('/api/adventure/journeys/%s/checkpoints', $journey->getId()));
        self::assertResponseIsSuccessful();
        self::assertMatchesResourceCollectionJsonSchema(Checkpoint::class);
    }
}
