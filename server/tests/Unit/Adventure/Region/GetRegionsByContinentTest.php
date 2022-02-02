<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Region;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\Region;
use App\Adventure\Gateway\RegionGateway;
use App\Adventure\UseCase\Region\GetRegionsByContinent\GetRegionsByContinent;
use App\Adventure\UseCase\Region\GetRegionsByContinent\GetRegionsByContinentHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetRegionsByContinentTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetRegionsByContinent(): void
    {
        $continent = new Continent();
        $continent->setId(new Ulid());
        $continent->setName('Monde');

        $retrieveRegionsByContinent = new GetRegionsByContinent($continent);

        $region = new Region();
        $region->setContinent($continent);
        $region->setId(new Ulid());
        $region->setName('Region');

        $regionGateway = self::createMock(RegionGateway::class);
        $regionGateway
            ->expects(self::once())
            ->method('getRegionsByContinent')
            ->with(self::equalTo($continent))
            ->willReturn([$region]);

        $handler = new GetRegionsByContinentHandler($regionGateway);

        /** @var array<int, Region> $regions */
        $regions = $handler($retrieveRegionsByContinent);

        self::assertCount(1, $regions);
        self::assertEquals('Region', $regions[0]->getName());
        self::assertEquals($continent, $regions[0]->getContinent());
    }
}
