<?php

declare(strict_types=1);

namespace App\Adventure\Query;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use App\Adventure\Message\RetrieveWorlds;
use App\Core\CQRS\HandlerInterface;

final class RetrieveWorldsHandler implements HandlerInterface
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
    public function __invoke(RetrieveWorlds $retrieveWorlds): array
    {
        return $this->worldGateway->getWorlds();
    }
}
