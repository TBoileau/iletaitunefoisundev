<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Tests\Functional\ApiTestCase;
use Generator;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class LoginTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldBeAuthenticated(): void
    {
        $client = self::post('/api/login_check', self::createData(), false);

        self::assertResponseIsSuccessful();

        /** @var array{token: string, refresh_token: string} $content */
        $content = self::getContent($client->getResponse());

        /** @var JWTTokenManagerInterface $jwtManager */
        $jwtManager = $client->getContainer()->get('lexik_jwt_authentication.jwt_manager');

        /** @var RefreshTokenManagerInterface $refreshTokenManager */
        $refreshTokenManager = $client->getContainer()->get('gesdinet.jwtrefreshtoken.refresh_token_manager');

        /** @var array{username: string} $payload */
        $payload = $jwtManager->parse($content['token']);

        /** @var RefreshTokenInterface $refreshToken */
        $refreshToken = $refreshTokenManager->get($content['refresh_token']);

        self::assertEquals('user+1@email.com', $payload['username']);
        self::assertEquals('user+1@email.com', $refreshToken->getUsername());
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
        self::post('/api/login_check', $data, false);

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
