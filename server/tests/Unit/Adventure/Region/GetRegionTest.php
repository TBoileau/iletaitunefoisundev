<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adventure\Region;

use App\Adventure\Entity\Region;
use App\Adventure\Gateway\RegionGateway;
use App\Adventure\UseCase\Region\GetRegion\GetRegion;
use App\Adventure\UseCase\Region\GetRegion\GetRegionHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class GetRegionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetRegion(): void
    {
        $regionId = new Ulid();

        $region = new Region();
        $region->setId($regionId);
        $region->setName('Region');

        $regionGateway = self::createMock(RegionGateway::class);
        $regionGateway
            ->expects(self::once())
            ->method('getRegionById')
            ->with(self::equalTo((string) $regionId))
            ->willReturn($region);

        $handler = new GetRegionHandler($regionGateway);

        $getRegion = new GetRegion((string) $regionId);

        $region = $handler($getRegion);

        self::assertEquals('Region', $region->getName());
    }
}
