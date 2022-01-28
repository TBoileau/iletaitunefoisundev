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

    public static function get(string $route, bool $needToken = true): KernelBrowser
    {
        $client = $needToken ? self::createAuthenticatedClient() : self::createClient();

        $client->request(
            Request::METHOD_GET,
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json'],
        );

        return $client;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function post(string $route, array $data, bool $needToken = true): KernelBrowser
    {
        $client = $needToken ? self::createAuthenticatedClient() : self::createClient();

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

        return $client;
    }

    private static function createAuthenticatedClient(): KernelBrowser
    {
        $client = self::post(
            '/api/login_check',
            [
                'email' => 'user+1@email.com',
                'password' => 'password',
            ],
            false
        );

        /** @var array{token: string, refresh_token: string} $content */
        $content = self::getContent($client->getResponse());

        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $content['token']));

        return $client;
    }
}
