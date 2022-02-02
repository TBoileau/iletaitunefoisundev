<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Region\GetRegionsByContinent;

use App\Adventure\Entity\Region;
use App\Adventure\Gateway\RegionGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetRegionsByContinentHandler implements QueryHandlerInterface
{
    /**
     * @param RegionGateway<Region> $regionGateway
     */
    public function __construct(private RegionGateway $regionGateway)
    {
    }

    /**
     * @return array<array-key, Region>
     */
    public function __invoke(GetRegionsByContinent $findRegionsByWorld): array
    {
        return $this->regionGateway->getRegionsByContinent($findRegionsByWorld->getId());
    }
}
