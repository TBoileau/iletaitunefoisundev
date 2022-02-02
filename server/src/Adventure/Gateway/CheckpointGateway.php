<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Checkpoint;

/**
 * @template T
 */
interface CheckpointGateway
{
    public function save(Checkpoint $checkpoint): void;

    public function getCheckpointById(string $id): Checkpoint;
}
