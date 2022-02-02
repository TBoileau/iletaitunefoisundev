<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Continent\GetContinentsByWorld;

use App\Adventure\Entity\Continent;
use App\Adventure\Gateway\ContinentGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetContinentsByWorldHandler implements QueryHandlerInterface
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
    public function __invoke(GetContinentsByWorld $getContinentsByWorld): array
    {
        return $this->continentGateway->getContinentsByWorld($getContinentsByWorld->getWorld());
    }
}
