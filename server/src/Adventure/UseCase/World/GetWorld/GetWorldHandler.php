<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\World\GetWorld;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetWorldHandler implements QueryHandlerInterface
{
    /**
     * @param WorldGateway<World> $worldGateway
     */
    public function __construct(private WorldGateway $worldGateway)
    {
    }

    public function __invoke(GetWorld $getWorld): World
    {
        return $this->worldGateway->getWorldById($getWorld->getId());
    }
}
