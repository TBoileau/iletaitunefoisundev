<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\World\GetWorlds;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetWorldsHandler implements QueryHandlerInterface
{
    /**
     * @param WorldGateway<World> $worldGateway
     */
    public function __construct(private WorldGateway $worldGateway)
    {
    }

    /**
     * @return array<array-key, World>
     */
    public function __invoke(GetWorlds $findWorlds): array
    {
        return $this->worldGateway->getWorlds();
    }
}
