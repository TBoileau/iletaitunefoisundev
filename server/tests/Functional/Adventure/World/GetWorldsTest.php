<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure\World;

use App\Tests\Functional\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class GetWorldsTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnsWorlds(): void
    {
        $client = self::createAuthenticatedClient();

        /** @var array<array-key, array{id: string, name: string}> $content */
        $content = self::get($client, '/api/adventure/worlds');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertCount(1, $content);
        self::assertTrue(Ulid::isValid($content[0]['id']));
        self::assertEquals('Monde', $content[0]['name']);
    }
}
