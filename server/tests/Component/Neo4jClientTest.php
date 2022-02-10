<?php

declare(strict_types=1);

namespace App\Tests\Component;

use Laudis\Neo4j\Contracts\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class Neo4jClientTest extends KernelTestCase
{
    /**
     * @test
     */
    public function shouldReturnNeo4jClient(): void
    {
        self::bootKernel();

        /** @var mixed $neo4jClient */
        $neo4jClient = self::getContainer()->get(ClientInterface::class);

        self::assertInstanceOf(ClientInterface::class, $neo4jClient);
    }
}
