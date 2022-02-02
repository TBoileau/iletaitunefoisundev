<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Region\GetRegionsByContinent;

use App\Adventure\Entity\Continent;
use App\Core\Bus\Query\QueryInterface;
use Symfony\Component\Validator\Constraints\NotNull;

final class GetRegionsByContinent implements QueryInterface
{
    #[NotNull]
    private Continent $continent;

    public function __construct(Continent $continent)
    {
        $this->continent = $continent;
    }

    public function getContinent(): Continent
    {
        return $this->continent;
    }
}
