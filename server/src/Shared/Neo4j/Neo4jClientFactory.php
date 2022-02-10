<?php

declare(strict_types=1);

namespace App\Shared\Neo4j;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\ClientInterface;

final class Neo4jClientFactory
{
    public static function create(string $url): ClientInterface
    {
        return ClientBuilder::create()
            ->withDriver('bolt', $url)
            ->withDefaultDriver('bolt')
            ->build();
    }
}
