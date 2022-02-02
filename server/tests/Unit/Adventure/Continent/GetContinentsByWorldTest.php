<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Continent;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Adventure\Gateway\ContinentGateway;
use App\Adventure\UseCase\Continent\GetContinentsByWorld\GetContinentsByWorld;
use App\Adventure\UseCase\Continent\GetContinentsByWorld\GetContinentsByWorldHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetContinentsByWorldTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetContinentsByWorld(): void
    {
        $worldId = new Ulid();

        $world = new World();
        $world->setId($worldId);
        $world->setName('Monde');

        $retrieveContinentsByWorld = new GetContinentsByWorld((string) $worldId);

        $continent = new Continent();
        $continent->setWorld($world);
        $continent->setId(new Ulid());
        $continent->setName('Continent');

        $continentGateway = self::createMock(ContinentGateway::class);
        $continentGateway
            ->expects(self::once())
            ->method('getContinentsByWorld')
            ->with(self::equalTo((string) $worldId))
            ->willReturn([$continent]);

        $handler = new GetContinentsByWorldHandler($continentGateway);

        /** @var array<int, Continent> $continents */
        $continents = $handler($retrieveContinentsByWorld);

        self::assertCount(1, $continents);
        self::assertEquals('Continent', $continents[0]->getName());
        self::assertEquals($world, $continents[0]->getWorld());
    }
}
