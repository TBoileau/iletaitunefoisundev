<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;

/**
 * @template T
 */
interface ContinentGateway
{
    /**
     * @return array<array-key, Continent>
     */
    public function getContinentsByWorld(World $world): array;
}
