<?php

declare(strict_types=1);

namespace App\Domain\Shared\Gateway;

use App\Domain\Shared\Entity\Node;

/**
 * @template T
 */
interface NodeGateway
{
    public function update(): void;
}
