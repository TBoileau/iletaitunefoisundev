<?php

declare(strict_types=1);

namespace App\Domain\Node\Gateway;

/**
 * @template T
 */
interface NodeGateway
{
    public function update(): void;
}
