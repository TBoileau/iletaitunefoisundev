<?php

declare(strict_types=1);

namespace App\Adventure\Action\World;

use App\Adventure\Entity\World;
use App\Adventure\UseCase\World\GetWorlds\GetWorlds;
use App\Adventure\ViewModel\WorldsViewModel;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/worlds',
    name: 'get_worlds',
    methods: [Request::METHOD_GET]
)]
final class GetWorldsAction implements ActionInterface
{
    public function __invoke(QueryBusInterface $queryBus): WorldsViewModel
    {
        /** @var array<array-key, World> $worlds */
        $worlds = $queryBus->fetch(new GetWorlds());

        return WorldsViewModel::createFromWorlds($worlds);
    }
}
