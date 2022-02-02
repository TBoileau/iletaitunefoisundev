<?php

declare(strict_types=1);

namespace App\Adventure\Action\World;

use App\Adventure\Entity\World;
use App\Adventure\UseCase\World\GetWorld\GetWorld;
use App\Adventure\ViewModel\WorldViewModel;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/worlds/{id}',
    name: 'get_world',
    requirements: ['id' => '%routing.ulid%'],
    methods: [Request::METHOD_GET]
)]
final class GetWorldAction implements ActionInterface
{
    public function __invoke(QueryBusInterface $queryBus, string $id): WorldViewModel
    {
        /** @var World $world */
        $world = $queryBus->fetch(new GetWorld($id));

        return WorldViewModel::createFromWorld($world);
    }
}
