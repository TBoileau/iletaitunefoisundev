<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\Continent;
use JsonSerializable;

final class ContinentsViewModel implements JsonSerializable
{
    /**
     * @param array<array-key, ContinentViewModel> $continents
     */
    private function __construct(public array $continents)
    {
    }

    /**
     * @param array<array-key, Continent> $continents
     */
    public static function createFromContinents(array $continents): ContinentsViewModel
    {
        return new self(array_map([ContinentViewModel::class, 'createFromContinent'], $continents));
    }

    /**
     * @return array<array-key, ContinentViewModel>
     */
    public function jsonSerialize(): array
    {
        return $this->continents;
    }
}
