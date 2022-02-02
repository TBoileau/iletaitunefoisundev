<?php

declare(strict_types=1);

namespace App\Adventure\Action\Region;

use App\Adventure\Entity\Region;
use App\Adventure\UseCase\Region\GetRegionsByContinent\GetRegionsByContinent;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/continents/{id}/regions',
    name: 'get_regions_by_continent',
    requirements: ['id' => '%routing.ulid%'],
    methods: [Request::METHOD_GET]
)]
final class GetRegionsByContinentAction implements ActionInterface
{
    /**
     * @return array<array-key, Region>
     */
    public function __invoke(QueryBusInterface $queryBus, string $id): array
    {
        /** @var array<array-key, Region> $regions */
        $regions = $queryBus->fetch(new GetRegionsByContinent($id));

        return $regions;
    }
}
