<?php

declare(strict_types=1);

namespace App\Adventure\Query;

use App\Adventure\Entity\Continent;
use App\Adventure\Gateway\ContinentGateway;
use App\Adventure\Message\RetrieveContinentsByWorld;
use App\Core\CQRS\HandlerInterface;

final class RetrieveContinentsByWorldHandler implements HandlerInterface
{
    /**
     * @param ContinentGateway<Continent> $continentGateway
     */
    public function __construct(private ContinentGateway $continentGateway)
    {
    }

    /**
     * @return array<array-key, Continent>
     */
    public function __invoke(RetrieveContinentsByWorld $retrieveContinentsByWorld): array
    {
        return $this->continentGateway->getContinentsByWorld($retrieveContinentsByWorld->world);
    }
}
