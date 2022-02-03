<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LoginTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldBeAuthenticated(): void
    {
        $client = self::createClient();
        $client->request(
            Request::METHOD_POST,
            '/api/security/login',
            [
                'json' => self::createData(),
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );
        self::assertResponseIsSuccessful();
    }

    /**
     * @param array<string, string> $data
     *
     * @test
     *
     * @dataProvider provideInvalidData
     */
    public function shouldNotBeAuthenticatedDueToInvalidData(array $data): void
    {
        $client = self::createClient();
        $client->request(
            Request::METHOD_POST,
            '/api/security/login',
            [
                'json' => $data,
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        yield 'wrong email' => [self::createData(['email' => 'fail@email.com'])];
        yield 'empty email' => [self::createData(['email' => ''])];
        yield 'wrong password' => [self::createData(['password' => 'fail'])];
        yield 'empty password' => [self::createData(['password' => ''])];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(array $extra = []): array
    {
        return $extra + [
                'email' => 'user+1@email.com',
                'password' => 'password',
            ];
    }
}
