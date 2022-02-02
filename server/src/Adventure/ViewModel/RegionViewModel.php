<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\Region;

final class RegionViewModel
{
    private function __construct(public string $id, public string $name)
    {
    }

    public static function createFromRegion(Region $region): RegionViewModel
    {
        return new self((string) $region->getId(), $region->getName());
    }
}
