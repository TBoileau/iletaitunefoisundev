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

    public static function get(string $route): KernelBrowser
    {
        $client = self::createClient();

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
    public static function post(string $route, array $data): KernelBrowser
    {
        $client = self::createClient();

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
}
