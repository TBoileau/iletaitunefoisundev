<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    /**
     * @return array<array-key, mixed>
     */
    public static function getContent(Response $response): array
    {
        /** @var string $content */
        $content = $response->getContent();

        /** @var array<array-key, mixed> $decodedContent */
        $decodedContent = json_decode($content, true);

        return $decodedContent;
    }

    /**
     * @return array<array-key, mixed>
     */
    public static function get(KernelBrowser $client, string $route): array
    {
        $client->request(
            Request::METHOD_GET,
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json'],
        );

        return self::getContent($client->getResponse());
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<array-key, mixed>
     */
    public static function post(KernelBrowser $client, string $route, array $data): array
    {
        /** @var string $body */
        $body = json_encode($data);

        $client->request(
            Request::METHOD_POST,
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json'],
            $body
        );

        return self::getContent($client->getResponse());
    }

    public static function createAuthenticatedClient(): KernelBrowser
    {
        $client = self::createClient();

        /** @var array{token: string, refresh_token: string} $content */
        $content = self::post(
            $client,
            '/api/login_check',
            [
                'email' => 'user+1@email.com',
                'password' => 'password',
            ],
        );

        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $content['token']));

        return $client;
    }
}
