<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\Region;
use JsonSerializable;

final class RegionsViewModel implements JsonSerializable
{
    /**
     * @param array<array-key, RegionViewModel> $regions
     */
    private function __construct(public array $regions)
    {
    }

    /**
     * @param array<array-key, Region> $regions
     */
    public static function createFromRegions(array $regions): RegionsViewModel
    {
        return new self(array_map([RegionViewModel::class, 'createFromRegion'], $regions));
    }

    /**
     * @return array<array-key, RegionViewModel>
     */
    public function jsonSerialize(): array
    {
        return $this->regions;
    }
}
