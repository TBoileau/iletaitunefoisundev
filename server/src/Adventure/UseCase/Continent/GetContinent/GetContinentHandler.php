<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Continent\GetContinent;

use App\Adventure\Entity\Continent;
use App\Adventure\Gateway\ContinentGateway;
use App\Core\Bus\Query\QueryHandlerInterface;

final class GetContinentHandler implements QueryHandlerInterface
{
    /**
     * @param ContinentGateway<Continent> $continentGateway
     */
    public function __construct(private ContinentGateway $continentGateway)
    {
    }

    public function __invoke(GetContinent $getContinent): Continent
    {
        return $this->continentGateway->getContinentById($getContinent->getId());
    }
}
