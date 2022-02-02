<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Continent;

/**
 * @template T
 */
interface ContinentGateway
{
    /**
     * @return array<array-key, Continent>
     */
    public function getContinentsByWorld(string $id): array;

    public function getContinentById(string $id): Continent;
}
