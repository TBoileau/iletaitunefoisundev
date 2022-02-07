<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestForgottenPasswordTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldAcceptThePostRequestForValidEmail(): void
    {
        $client = self::createClient();
        $response = $client->request(
            Request::METHOD_POST,
            '/api/security/forgotten-password/request',
            ['json' => self::createData('user+1@email.com')]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
        self::assertEquals('', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldAcceptThePostRequestForValidEmailButUnknownEmail(): void
    {
        $client = self::createClient();
        $client->request(
            Request::METHOD_POST,
            '/api/security/forgotten-password/request',
            ['json' => self::createData('user+unknown@email.com')]
        );
        self::assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
    }

    /**
     * @test
     * @dataProvider provideInvalidData
     *
     * @param array<array-key, array<string, string>> $data
     */
    public function shouldRejectForInvalidData(array $data): void
    {
        $client = self::createClient();
        $response = $client->request(
            Request::METHOD_POST,
            '/api/security/forgotten-password/request',
            ['json' => $data]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        yield 'invalid email' => [self::createData('fail')];
        yield 'empty email' => [self::createData('')];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(string $email, array $extra = []): array
    {
        return $extra + [
                'email' => $email,
            ];
    }
}
