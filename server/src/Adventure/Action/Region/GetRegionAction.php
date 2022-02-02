<?php

declare(strict_types=1);

namespace App\Adventure\Action\Region;

use App\Adventure\Entity\Region;
use App\Adventure\UseCase\Region\GetRegion\GetRegion;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/regions/{id}',
    name: 'get_region',
    requirements: ['id' => '%routing.ulid%'],
    methods: [Request::METHOD_GET]
)]
final class GetRegionAction implements ActionInterface
{
    public function __invoke(QueryBusInterface $queryBus, string $id): Region
    {
        /** @var Region $region */
        $region = $queryBus->fetch(new GetRegion($id));

        return $region;
    }
}
