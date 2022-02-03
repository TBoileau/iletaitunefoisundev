<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Security\Entity\User;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegistrationTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldRegisterAnUser(): void
    {
        $client = self::createClient();
        $client->request(
            Request::METHOD_POST,
            '/api/security/register',
            ['json' => self::createData()]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertJsonContains(['email' => 'user+6@email.com']);
        self::assertMatchesResourceItemJsonSchema(User::class);
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
        $client = self::createClient();
        $client->request(
            Request::METHOD_POST,
            '/api/security/register',
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
        yield 'invalid email' => [self::createData(['email' => 'fail'])];
        yield 'non unique email' => [self::createData(['email' => 'user+1@email.com'])];
        yield 'empty email' => [self::createData(['email' => ''])];
        yield 'wrong plain password' => [self::createData(['plainPassword' => 'fail'])];
        yield 'empty plain password' => [self::createData(['plainPassword' => ''])];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(array $extra = []): array
    {
        return $extra + [
                'email' => 'user+6@email.com',
                'plainPassword' => 'Password123!',
            ];
    }
}
