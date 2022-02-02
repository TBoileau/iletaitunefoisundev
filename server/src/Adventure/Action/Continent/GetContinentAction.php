<?php

declare(strict_types=1);

namespace App\Adventure\Action\Continent;

use App\Adventure\Entity\Continent;
use App\Adventure\UseCase\Continent\GetContinent\GetContinent;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/continents/{id}',
    name: 'get_continent',
    requirements: ['id' => '%routing.ulid%'],
    methods: [Request::METHOD_GET]
)]
final class GetContinentAction implements ActionInterface
{
    public function __invoke(QueryBusInterface $queryBus, string $id): Continent
    {
        /** @var Continent $continent */
        $continent = $queryBus->fetch(new GetContinent($id));

        return $continent;
    }
}
