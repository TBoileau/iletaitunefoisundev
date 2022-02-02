<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Region;

/**
 * @template T
 */
interface RegionGateway
{
    /**
     * @return array<array-key, Region>
     */
    public function getRegionsByContinent(string $id): array;

    public function getRegionById(string $id): Region;
}
