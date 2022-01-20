<?php

declare(strict_types=1);

namespace App\Domain\Shared\Gateway;

/**
 * @template T
 */
interface NodeGateway
{
    public function update(): void;
}
