<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Adventure\Gateway\ContinentGateway;
use App\Adventure\Message\RetrieveContinentsByWorld;
use App\Adventure\Query\RetrieveContinentsByWorldHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class RetrieveContinentsByWorldTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetContinentsByWorld(): void
    {
        $world = new World();
        $world->setId(new Ulid());
        $world->setName('Monde');

        $retrieveContinentsByWorld = new RetrieveContinentsByWorld($world);

        $continent = new Continent();
        $continent->setWorld($world);
        $continent->setId(new Ulid());
        $continent->setName('Continent');

        $continentGateway = self::createMock(ContinentGateway::class);
        $continentGateway
            ->expects(self::once())
            ->method('getContinentsByWorld')
            ->with(self::equalTo($world))
            ->willReturn([$continent]);

        $handler = new RetrieveContinentsByWorldHandler($continentGateway);

        /** @var array<int, Continent> $continents */
        $continents = $handler($retrieveContinentsByWorld);

        self::assertCount(1, $continents);
        self::assertEquals('Continent', $continents[0]->getName());
        self::assertEquals($world, $continents[0]->getWorld());
    }
}
