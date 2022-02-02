<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\Continent;

final class ContinentViewModel
{
    private function __construct(public string $id, public string $name)
    {
    }

    public static function createFromContinent(Continent $continent): ContinentViewModel
    {
        return new self((string) $continent->getId(), $continent->getName());
    }
}
