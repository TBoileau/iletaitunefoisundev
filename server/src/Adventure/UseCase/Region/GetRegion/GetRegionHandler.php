<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Region\GetRegion;

use App\Adventure\Entity\Region;
use App\Adventure\Gateway\RegionGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetRegionHandler implements QueryHandlerInterface
{
    /**
     * @param RegionGateway<Region> $regionGateway
     */
    public function __construct(private RegionGateway $regionGateway)
    {
    }

    public function __invoke(GetRegion $getRegion): Region
    {
        return $this->regionGateway->getRegionById($getRegion->getId());
    }
}
