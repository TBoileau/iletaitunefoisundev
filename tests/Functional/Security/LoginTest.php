<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use Generator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profile;

final class LoginTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldBeAuthenticated(): void
    {
        $client = self::createClient();

        /** @var string $content */
        $content = json_encode(self::createData());

        $client->request(
            Request::METHOD_POST,
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );

        self::assertResponseIsSuccessful();

        /** @var string $content */
        $content = $client->getResponse()->getContent();

        /** @var array{token: string} $response */
        $response = json_decode($content, true);

        /** @var JWTTokenManagerInterface $jwtManager */
        $jwtManager = $client->getContainer()->get('lexik_jwt_authentication.jwt_manager');

        /** @var array{username: string} $payload */
        $payload = $jwtManager->parse($response['token']);

        self::assertEquals('user+1@email.com', $payload['username']);
    }

    /**
     * @param array<string, string> $formData
     *
     * @test
     *
     * @dataProvider provideInvalidData
     */
    public function shouldNotBeAuthenticatedDueToInvalidData(array $formData): void
    {
        $client = self::createClient();

        /** @var string $content */
        $content = json_encode($formData);

        $client->request(
            Request::METHOD_POST,
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
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
