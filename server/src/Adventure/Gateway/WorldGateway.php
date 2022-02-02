<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\World;

/**
 * @template T
 */
interface WorldGateway
{
    /**
     * @return array<array-key, World>
     */
    public function getWorlds(): array;

    public function getWorldById(string $id): World;
}
