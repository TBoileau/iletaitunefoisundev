<?php

declare(strict_types=1);

namespace App\Adventure\Action\Continent;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Adventure\UseCase\Continent\GetContinentsByWorld\GetContinentsByWorld;
use App\Core\Bus\Query\QueryBusInterface;
use App\Core\Http\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/worlds/{id}/continents', name: 'get_continents_by_world', methods: [Request::METHOD_GET])]
final class GetContinentsByWorldAction implements ActionInterface
{
    /**
     * @return array<array-key, Continent>
     */
    public function __invoke(QueryBusInterface $queryBus, World $world): array
    {
        /** @var array<array-key, Continent> $continents */
        $continents = $queryBus->fetch(new GetContinentsByWorld($world));

        return $continents;
    }
}
