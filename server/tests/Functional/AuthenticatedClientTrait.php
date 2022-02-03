<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpFoundation\Request;

trait AuthenticatedClientTrait
{
    public static function createAuthenticatedClient(): Client
    {
        $client = self::createClient();
        $response = $client->request(
            Request::METHOD_POST,
            '/api/security/login',
            [
                'json' => [
                    'email' => 'user+1@email.com',
                    'password' => 'password',
                ],
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ]
        );

        /** @var array{token: string, refresh_token: string} $content */
        $content = $response->toArray();

        $client->setDefaultOptions([
            'headers' => ['authorization' => sprintf('Bearer %s', $content['token'])],
        ]);

        return $client;
    }
}
