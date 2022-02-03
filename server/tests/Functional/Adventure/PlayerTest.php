<?php

declare(strict_types=1);

namespace App\Tests\Functional\Adventure;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Adventure\Entity\Player;
use App\Tests\Functional\AuthenticatedClientTrait;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PlayerTest extends ApiTestCase
{
    use AuthenticatedClientTrait;

    /**
     * @test
     */
    public function shouldCreatePlayer(): void
    {
        $client = self::createAuthenticatedClient('user+6@email.com');
        $client->request(
            Request::METHOD_POST,
            '/api/adventure/players',
            ['json' => self::createData()]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertJsonContains(['name' => 'Joueur 0']);
        self::assertMatchesResourceItemJsonSchema(Player::class);
    }

    /**
     * @param array<string, string> $data
     *
     * @test
     *
     * @dataProvider provideInvalidData
     */
    public function shouldNotRegisterDueToInvalidData(array $data): void
    {
        $client = self::createAuthenticatedClient('user+6@email.com');
        $client->request(
            Request::METHOD_POST,
            '/api/adventure/players',
            [
                'json' => $data,
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        yield 'empty name' => [self::createData(['name' => ''])];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(array $extra = []): array
    {
        return $extra + [
                'name' => 'Joueur 0',
            ];
    }
}
